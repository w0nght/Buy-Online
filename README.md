# Buy-Online
A web-based online shopping system using Ajax technologies and PHP.

## System info

To use the function of booking a taxi in CabsOnline, user need to be a register customer of the system.

### For customers
New customer can registe to the system via the main page on BuyOnline. <br>
After register, customer will be prompted with a link to head back to the main page, customer can log in to the system by entering their email address and password in the login page. <br>
After login, customer will be sent to the buying page, where the customer can select the item they wish to buy, add items to their shopping cart. <br>
Customer then can choose between discard the shopping cart, or confirm the purchase.

### For managers
Managers can access the system on the login page with the pre-set login credential (see below). <br>
After login, managers can add purchaseble items to the system in the listing page and process the sold items in processing page. <br>

#### List of files in the system
### addToCart.php
server file, to control the "Add to cart" button in Buying page, handle the update of the item and send item to the shopping cart

### buying.htm
html page of buying

#### buying.php
server file, to retrieve all item where the quantity is greater than zero, using xpath
#### buyonline.htm
html page of BuyOnline main page, link to customer register page and login page, also to the manager login page
