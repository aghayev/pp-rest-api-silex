
curl http://pp-api.local/1.0/seller -d '{"logo_name":"dezrezlegal.png","name":"Dezrez Legal","address_line_1":"Technium Building","address_line_2":"Kings Cross","address_line_3":"Swansea","post_code":"SA1 8PJ","telephone":"02011112222","email":"dezrez@legal.com","vat":"VAT1","password":"password123"}' -H 'Content-Type: application/json'

curl http://api.pleasepay.co.uk/1.0/seller -d '{"logo_name":"dezrezlegal.png","name":"Dezrez Legal","address_line_1":"Technium Building","address_line_2":"Kings Cross","address_line_3":"Swansea","post_code":"SA1 8PJ","telephone":"02011112222","email":"dezrez@legal.com","vat":"VAT1","password":"password123"}' -H 'Content-Type: application/json'

curl http://pp-mytimeout/1.0/seller -d '{"logo_name":"dezrezlegal.png","name":"Dezrez Legal","address_line_1":"Technium Building","address_line_2":"Kings Cross","address_line_3":"Swansea","post_code":"SA1 8PJ","telephone":"02011112222","email":"dezrez@legal.com","vat":"VAT1","password":"password123"}' -H 'Content-Type: application/json'

curl http://api.pleasepay.co.uk/1.0/seller -d '{"name":"ting","email":"edfrd@hsain.uk"}' -H 'Content-Type: application/json'

-==-- Authorized seller email lookup with details -==-
curl http://pp-api.local/1.0/seller/dezr -H "Client-Security-Token: e424673de6d09f40f2bdd6ab9b1596ddadf999ef7c406e25"

-==- Nonauthorized seller email lookup -==-
curl http://pp-api.local/1.0/seller/dezr

-==- Authorized seller details update -==-
curl http://pp-api.local/1.0/update/seller -d '{"seller_id":"3","token":"e424673de6d09f40f2bdd6ab9b1596ddadf999ef7c406e25", "logo_name":"dezrezlegal.png","name":"Dezrez Verigal","address_line_1":"Technium Building","address_line_2":"Kings Cross","address_line_3":"Swansea","post_code":"SA1 8PJ","telephone":"02011112222","vat":"VAT1","password":"password123"}' -H 'Content-Type: application/json'

