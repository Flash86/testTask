# testTask

`composer update`  
Sql dump you can search in /sqlDump.
Please, set base params in /Configuration/Development/Settings.yaml and set permission params for folders  
`sudo ./flow core:setfilepermissions yourUser www-data www-data`


**Demo user**: login testUser, password 11111

Route | Description | Method | Params
--- | --- | --- | ---
/product/{productId} | Get product by id | GET | (integer) productId
/category/{categoryId}/products | Get products of category | GET | (integer) categoryId, (integer) offset, (integer) limit, (string) order (can be title, price, quantity )
/products/{productId}/photos | Get products photos | GET | (integer) productId
/user | Get autorizated userId | GET | 
/user/login | User login | POST | (string) login=testUser, (string) password=11111
/user/logout | User logout | GET | 
/cart/curency/{curencyId} | Get cart | GET | (string) curencyId
/cart/add | Ecommerce :: Add product to cart | POST | (integer) productId, (integer) count
/cart/remove | Ecommerce :: Remove product from cart | POST | (integer) productId
/cart/clear | Clear cart | GET | 
/order/curency/{curencyId} | Order | GET | (string) curencyId
/invoice/{orderId} | get invoice | GET | (integer) orderId