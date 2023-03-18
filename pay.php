<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flutterwave Test</title>
</head>

<body>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script>
        getpaidSetup({
            PBFPubKey: "FLWPUBK_TEST-308e76d9c3020abf4b61a148a86c1a06-X",
            customer_email: "biodunhi@gmail.com",
            customer_firstname: "Adeleye",
            customer_lastname: "Ayodeji",
            custom_description: "Description",
            custom_logo: "https://www.aqskill.com/wp-content/uploads/2020/05/logo-pde.png",
            custom_title: "Payment",
            amount: 3000,
            customer_phone: "0495054930",
            currency: "NGN",
            payment_options: "banktransfer, ussd",
            txref: "sdsdswewewe",
            onClose: function() {
                console.log("Closed");
            },
            callback: (response) => {
                console.log(response);
            },
        });
    </script>
</body>

</html>