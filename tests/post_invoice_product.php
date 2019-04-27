
curl http://pp-api.local/1.0/invoice_product -d '{"seller_id":"1", "invoice_id":"1", "number":"0002", "description":"something new", "price":"15.10", "quantity":"3"}' -H 'Content-Type: application/json'


curl http://api.pleasepay.co.uk/1.0/invoice_product -d '{"seller_id":"1", "invoice_id":"1", "number":"0001", "description":"something new", "price":"15.10", "quantity":"3"}' -H 'Content-Type: application/json'
