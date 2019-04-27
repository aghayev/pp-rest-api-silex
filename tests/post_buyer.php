
curl http://pp-api.local/1.0/buyer -d '{"account_number":"36356356","name":"LAMH Heating","email":"lamh@heating.co.uk","address_line_1":"46 Gloucester Road","address_line_2":"Kingston","address_line_3":"Middx","post_code":"KT2 8PS","telephone":"0743563532","vat":"VAF1","delivery_name":"LMH Heating","delivery_address_line_1":"46 Gloucester Road","delivery_address_line_2":"Kingston","delivery_address_line_3":"Middx","delivery_post_code":"KT2 8PS"}' -H 'Content-Type: application/json'

curl http://api.pleasepay.co.uk/buyer -d '{"account_number":"36356356","name":"LMH Heating","email":"lmh@heating.co.uk","address_line_1":"46 Gloucester Road","address_line_2":"Kingston","address_line_3":"Middx","post_code":"KT2 8PS","telephone":"0743563532","vat":"VAF1","delivery_name":"LMH Heating","delivery_address_line_1":"46 Gloucester Road","delivery_address_line_2":"Kingston","delivery_address_line_3":"Middx","delivery_post_code":"KT2 8PS"}' -H 'Content-Type: application/json'

curl http://pp-api.local/1.0/buyer -d '{"name":"ting","email":"edfrd@hsain.uk"}' -H 'Content-Type: application/json'

curl http://api.pleasepay.co.uk/1.0/buyer -d '{"name":"ting","email":"edfrd@hsain.uk"}' -H 'Content-Type: application/json'

curl http://pp-api.local/1.0/buyer -H "Client-Security-Token: 2ceb0c9b4c54b0c5b1d187c547e7da1f769b9ca146dc3174"

curl http://api.pleasepay.co.uk/1.0/buyer -H "Client-Security-Token: 84144d18b79c35d2774bd7f138fd9b9aeafa1ec5bfa3d1ff"
