.PHONY: dev dev-monitoring down logs

dev:
	./deploy/bin/dev-up.sh

dev-monitoring:
	WITH_MONITORING=1 ./deploy/bin/dev-up.sh

down:
	./deploy/bin/dev-down.sh

logs:
	docker compose logs --follow --tail=200 app web horizon scheduler

