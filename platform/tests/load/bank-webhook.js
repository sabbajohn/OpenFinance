import { check } from 'k6';
import exec from 'k6/execution';
import http from 'k6/http';

const webhookUrl = __ENV.WEBHOOK_URL;
const webhookToken = __ENV.BANK_WEBHOOK_TOKEN;

export const options = {
    scenarios: {
        sustained: {
            executor: 'constant-arrival-rate',
            rate: 200,
            timeUnit: '1s',
            duration: __ENV.SUSTAINED_DURATION || '10m',
            preAllocatedVUs: 100,
            maxVUs: 1000,
        },
        burst: {
            executor: 'constant-arrival-rate',
            rate: 500,
            timeUnit: '1s',
            duration: __ENV.BURST_DURATION || '1m',
            startTime: __ENV.SUSTAINED_DURATION || '10m',
            preAllocatedVUs: 250,
            maxVUs: 2000,
        },
    },
    thresholds: {
        http_req_duration: ['p(95)<500'],
        http_req_failed: ['rate<0.001'],
        checks: ['rate>0.999'],
    },
};

export default function () {
    if (!webhookUrl || !webhookToken) {
        throw new Error('Defina WEBHOOK_URL e BANK_WEBHOOK_TOKEN.');
    }

    const eventId = `k6-${exec.scenario.name}-${exec.scenario.iterationInTest}`;
    const response = http.post(
        webhookUrl,
        JSON.stringify({
            id: eventId,
            type: 'pix.received',
            occurred_at: new Date().toISOString(),
            txid: eventId,
            status: 'ATIVA',
        }),
        {
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${webhookToken}`,
                'X-Event-Id': eventId,
            },
        },
    );

    check(response, {
        'ACK persistido': (result) => result.status === 202,
    });
}
