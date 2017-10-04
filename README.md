# paycorp payment gateway 

## components

`www` directory contains the all php codes and librarie. 
`payment_init.php` initialize the payment with gateway and load the payment
page on an iframe. 

in success payment app will redirects to the page `http://msc.scorelab.org:4000/payment_complete.php`
`payment_complete.php` handles the payment completion


## build and run with docker

```
docker build --tag erangaeb/zph:0.3 .
docker run -p 8080:80 -d erangaeb/zph:0.3
```
