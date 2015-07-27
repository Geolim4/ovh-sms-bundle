OVH Sms Bundle
===================

Get Credentials
-------------
Generate your API credentials on this page : https://eu.api.ovh.com/createToken/
You must add at least the following rights :

 - GET `/sms/`
 - POST `/sms/*/jobs/`


Installation using Composer
-------------

Add the following to your `composer.json` file :

    // composer.json
    {
    	// ...
    	"repositories": [
    		// ...
    		{
    			"type": "vcs",
    			"url": "git@sv5.a2zi.fr:a2zi/ovh_sms_bundle.git"
    		}
    	  ],
    	  // ...
    	  "require":{
    		  // ...
    		  "a2zi/ovh-sms-bundle": "dev-master"
    	  }
      }

Run commmand `composer update`

Register the bundle in your AppKernel.php file:

    <?php

    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new \A2zi\OvhSmsBundle\A2ziOvhSmsBundle(),
    );


Configuration
-------------

Configure OvhSmsBundle in config.yml :

    a2zi_ovh_sms:
      application_key: "your_application_key"
      application_secret: "your_application_secret"
      consumer_key: "your_application_consumer_key"
      endpoint: "endpoint_for_your_region"
      sms_service_id: "your_service_id"
      sender: "default_sender_name"

#### Mandatory parameters
Parameters **application_key**, **application_secret**, **consumer_key** are provided by Ovh. So, you need to generate your API credentials on this page : https://eu.api.ovh.com/createToken/

#### Optional parameters

 - **endpoint** : set the API endpoint (default is `ovh-eu`)
 - **sms_service_id**
 If it is not set, the service will call the Ovh API to get the first available SMS Service, and send another call to the API to send the message.
When this parameter is set, only one call to the API is necessary to send the message.

 - **sender** : set a default sender's name for messages. It must correspond to a sender defined in your account. To access to the list of available senders you can call the Ovh API from Ovh Console : https://api.ovh.com/console/#/sms/%7BserviceName%7D/senders#GET

Basic usage
-------------

In a controller file you can send a Sms with the following code

    $smsService = $this->get('ovh_sms');
    $sms = $smsService
			    ->addReceiver('+33601010101')
			    ->setMessage('Hello World !')
			    ->send();

