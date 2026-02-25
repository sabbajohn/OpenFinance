# # BranchIdentification

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | &gt; Tipo da dependência, segundo a regulamentação do Bacen,  na Resolução Nº 4072, de 26 de abril de 2012: Dependência de instituições financeiras e demais instituições, autorizadas a funcionar pelo Banco Central do Brasil, destinada à prática das atividades para as quais a instituição esteja regularmente habilitada.  * &#x60;AGENCIA&#x60;: Agência é a dependência destinada ao atendimento aos clientes, ao público em geral e aos associados de cooperativas de crédito, no exercício de atividades da instituição, não podendo ser móvel ou transitória;  * &#x60;POSTO_ATENDIMENTO&#x60;: Posto de Atendimento é a dependência subordinada a agência  ou à sede da instituição financeira, destinada ao atendimento ao público no exercício de uma ou mais de suas atividades, podendo ser fixo ou móvel. Segundo Art.15. Os Postos de Atendimento Bancário (PAB), Postos Avançados de Atendimento (PAA), Postos de Atendimento Transitórios (PAT), Postos de Compra de Ouro (PCO), Postos de Atendimento Cooperativo (PAC), Postos de Atendimento de Microcrédito (PAM), Postos Bancários de Arrecadação e Pagamento (PAP) e os Postos de Câmbio atualmente em funcionamento serão considerados PA.  * &#x60;POSTO_ATENDIMENTO_ELETRONICO&#x60;: Posto de Atendimento Eletrônico é a dependência constituída por um ou mais terminais de autoatendimento, subordinada a agência ou à sede da instituição, destinada à prestação de serviços por meio eletrônico, podendo ser fixo ou móvel, permanente ou transitório  * &#x60;UNIDADE_ADMINISTRATIVA_DESMEMBRADA &#x60;: Unidade Administrativa Desmembrada (UAD) segundo a Resolução 4072 , BCB, 2012, no Art. 8º \&quot;... é dependência destinada à execução de atividades administrativas da instituição, vedado o atendimento ao público\&quot; |
**code** | **string** | Código identificador da dependência |
**check_digit** | **string** | Dígito verificador do código da dependência |
**name** | **string** | Nome da dependência |
**related_branch** | **string** | Código da agência vinculada ao Posto de Atendimento - se aplicável | [optional]
**opening_date** | **string** | Data de abertura da dependência (uma string com data conforme especificação RFC-3339. p.ex. 2014-03-19) | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
