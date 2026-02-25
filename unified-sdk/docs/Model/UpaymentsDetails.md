# # Details

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**local_instrument** | [**\OpenAPI\Client\Model\EnumLocalInstrument**](EnumLocalInstrument.md) |  |
**qr_code** | **string** | Sequência de caracteres que corresponde ao QR Code disponibilizado para o pagador. É a sequência de caracteres que seria lida pelo leitor de QR Code, e deve propiciar o retorno dos dados do pagador após consulta na DICT. Essa funcionalidade é possível tanto para QR Code estático quanto para QR Code dinâmico. No arranjo do Pix esta é a mesma sequência gerada e/ou lida pela funcionalidade Pix Copia e Cola. Este campo deverá ser no formato UTF-8. [Restrição] Preenchimento obrigatório para pagamentos por QR Code, observado o tamanho máximo de 512 bytes. | [optional]
**proxy** | **string** | Chave cadastrada no DICT pertencente ao recebedor. Os tipos de chaves podem ser: telefone, e-mail, cpf/cnpj ou chave aleatória. No caso de telefone celular deve ser informado no padrão E.1641. Para e-mail deve ter o formato xxxxxxxx@xxxxxxx.xxx(.xx) e no máximo 77 caracteres. No caso de CPF deverá ser informado com 11 números, sem pontos ou traços. Para o caso de CNPJ deverá ser informado com 14 números, sem pontos ou traços. No caso de chave aleatória deve ser informado o UUID gerado pelo DICT, conforme formato especificado na RFC41223. Se informado, a detentora da conta deve validar o proxy no DICT quando localInstrument for igual a DICT, QRDN ou QRES e validar o campo creditorAccount. Esta validação é opcional caso o localInstrument for igual a INIC. [Restrição] Se localInstrument for igual a MANU, o campo proxy não deve ser preenchido. Se localInstrument for igual INIC, DICT, QRDN ou QRES, o campo proxy deve ser sempre preenchido com a chave Pix. | [optional]
**creditor_account** | [**\OpenAPI\Client\Model\CreditorAccount**](CreditorAccount.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
