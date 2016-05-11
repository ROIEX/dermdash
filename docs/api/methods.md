# Examples of available methods in api.

Base URL for api: {url}

Authorization only via access tokens.

Accepted types: HTTP Basic Auth, HTTP Bearer Auth and Query Param Auth (get param = access-token).

http://www.yiiframework.com/doc-2.0/guide-rest-authentication.html

## Examples of successful authorization.

* Query Param Auth: [http://example.com/api/v1/controller_name/method_name?access-token=asd89ASDjkhfd789dssgsdjkl5](#)
* HTTP Bearer Auth: Set Header param:

```
Authorization: Bearer asd89ASDjkhfd789dssgsdjkl5
```
* HTTP Basic Auth: You need username or email of user and password.

    Encode base64 username:password (example PHP function: base64('webmaster:webmaster') - result = d2VibWFzdGVyOndlYm1hc3Rlcg==).
    And set Header param:
    
```
Authorization: Basic d2VibWFzdGVyOndlYm1hc3Rlcg==
```


## Sign Up. [/user/signup] [post]

To get state_id - go to [States method](#get-states-all-states-and-active-states-get-datastates-get)

This method signs user up!

```
username = Column(String, nullable=False)
password = Column(String, nullable=False)
email = Column(String, nullable=False)
gender = Column(Integer, nullable=False) # Male = 0, Female = 1
date_of_birth = Column(Date, nullable=False) # example 2015-05-1
firstname = Column(String, nullable=False)
lastname = Column(String, nullable=False)
zipcode = Column(String, nullable=False)
rules_accept = Column(Boolean, nullable=False)
telemedicine_accept = Column(Boolean, nullable=False)
promo_code = Column(String, nullable=True)
```

Response:

```
{
  "success": true,
  "data": {
    "message": "Registration success!"
  }
}
```


## User Login [/user/login] [post]

You can send username or email via username field.

```
username = Column(String, nullable=False)
password = Column(String, nullable=False)
```

Response:

```
{
  "success": true,
  "data": {
    "id": 104,
    "username": "Test",
    "created_at": 1449600234,
    "access_token": "kyPRP0n1lfhzSNI-TOC7zNXlue9tS_q2TLidb4Hp"
  }
}
```



Response:

```
{
  "success": true,
  "data": {
    "id": 20,
    "user_id": 3,
    "sub_item_id": 3,
    "count": null,
    "add_item_id": null
  }
}
```

## Password Reset [/user/request-password-reset] [post]

```
email = Column(String, nullable=False)
```

Response:

```
{
    "success": true
    "data": [0]
}
```



## Inquiry photo upload [/inquiry-photo/create] [post]

Multipart-formdata

```
file = Column(File, nullable=False)
inquiry_id = Column(Integer, nullable=False)
```

Response:

```
{
  "success": true,
  "data": {
    "inquiry_id": "37",
    "url": "http://storage.botox.dev/cache/4/9PiAwjPVIpLhaTr4HJ4EKmUmFtFNBRiF.png?w=100&s=ed61e83f6bef2acd58347058375f12ac",
    "id": 4
  }
}
```

## Update Profile [/user-profile/update] [post]

Update current user profile.

```
firstname = Column(String, nullable=True)
lastname = Column(String, nullable=True)
gender = Column(Integer, nullable=True)
date_of_birth = Column(Date, nullable=True)
phone = Column(String, nullable=True)
address = Column(String, nullable=True)
zipcode = Column(String, nullable=True)
email = Column(String, nullable=True)
```

Response:

```
{
  "success": true,
  "data": {
    "firstname": "testasssss",
    "lastname": null,
    "gender": 1,
    "date_of_birth": "05/12/1993",
    "phone": null,
    "address": null,
    "state_id": 1,
    "city": "Odessa",
    "zipcode": "23532"
    "email": "testser@test.ru"
  }
}
```



## Get States (All states and active states) [get-data/states] [get]

Response has status of state and short name.

get-data/states - returns all states.

Example: [http://example.com/api/v1/get-data/states](#)

Response: 

```
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Alabama",
      "short_name": "AL",
      "status": 0
    },
    {
      "id": 2,
      "name": "Alaska",
      "short_name": "AK",
      "status": 0
    },
    {
      "id": 3,
      "name": "Arizona",
      "short_name": "AZ",
      "status": 0
    },
    {
      "id": 4,
      "name": "Arkansas",
      "short_name": "AR",
      "status": 0
    },
    {
      "id": 5,
      "name": "California",
      "short_name": "CA",
      "status": 1
    },
    {
      "id": 6,
      "name": "Colorado",
      "short_name": "CO",
      "status": 0
    }
    ...
  ]
}
```

get-data/states?active=true - returns active states.

Example: [http://example.com/api/v1/get-data/states?active=true](#)

```
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "California",
      "short_name": "CA",
      "status": 1
    }
  ]
}
```


## List Body Parts [/get-data/body-parts] [get]

Response:

```
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Chest",
      "description": "Description"
    }
  ]
}
```


## Invite User [/promo-code/invite] [post]

Fields:

```
email = Column(String, nullable=True) -- if not null - promo_code will be sent to this email.
```

Response:

```
{
  "success": true,
  "data": {
    "promo_code": "rHbWwH"
  }
}
```


## Use promo code [/promo-code/check] [post]

Fields:

```
promo_code = Column(String, nullable=False)
```

Response:

```
{
  "success": true,
  "data": {
    "discount_size": "20"
    "message": "Promo code applied."
  }
}
```


## Get Discount With Promo Code [/payment/get-discount] [get]

Fields:

```
inquiry_doctor_id = Column(Integer, nullable=False) array only
discount_size = Column(Float, nullable=True)
```

Response: 

```
{
  "success": true,
  "data": {
     "rewards_amount_used": 20,
     "rewards_amount_earned" => 50,
     "discount_amount_used" => 15,
     "summary_discount" => 35,
     "final_price" => 145
  }
}
```


## Get rewards [/payment/get-reward] [get]

Response:

```
{
  "success": true,
  "data": {
    "reward_count": 40
  }
}
```


## Payment [/payment/pay] [post]

Fields: 

```
inquiry_doctor_id = Column(Integer, nullable=False) -- get id from method get-doctor-offers
stripeToken = Column(String, nullable=False)
promo_code = Coulmn(String, nullable=True)
```

Response:

```
{
  "success": true,
  "data": [
    "success"
  ]
}
```


## Get doctor inquiry list [/inquiry/get-doctor-list] [post]

This method is used for payment. Id is inquiry_doctor_id in payment method.

Fields: 

```
inquiry_id = Column(Integer, nullable=False)
```

Response:

```
{
  "success": true,
  "data": [
    {
      "doctor_id": 4, -- Use this id for getting in method get inquiry doctor offers.
      "clinic": "John",
      "distance": "10 mile"
      "city": "Los Angeles",
      "photo": "http://localhost/botox/storage/web/source/1/j4DnpszEy7epcUMf_N8QY0SRhbs7vLRG.png"
      "price": "123",
      "photo": false,
      "rating": {
        "stars": null,
        "reviews": null
      },
      "time_after_create": 942 -- in seconds.
    }
  ]
}
```


## Get payments history. [/inquiry/get-history] [get]

Response:

```
{
  "success": true,
  "data": [
    {
      "doctor_photo": "http://storage.botox.dev/cache/1/j1joJCrV7ecalToJAWEDE84utBZqZgQc.jpg?w=200&s=29b12edfdbf5b733edbe271bfd59ab32",
      "doctor_name": "John",
      "doctor_surname": "Doe",
      "price": "576",
      "created_at": 1455626047,
      "paid_at": 1455628882,
      "procedure_name": "Chemical Peel",
      "rating": {
        "stars": 5,
        "reviews": 122
      }
    }
  ]
}
```

## Get brands data [/get-data/brands-data] [get]

Response:

```
{
  "success": true,
  "data": [
    {
      "brand_name": "Belotero",
      "sub_string": "per 1cc cyringe",
      "instruction": "Select the number of suringes you would like to quote for:",
      "icon_url": "http://storage.botox.dev/cache/3/HbfxGvgVSnJfcHfhSyxBDP2tcOMA3QMx.png?w=200&s=32b885ae8ca96f7c4aaebe52cb73339b",
      "treatment_id": null,
      "is_dropdown": null, -- bool, if needed dropdownlist.
      "param_multiselect": 0, 1 if multiselect is allowed
      "params": [
        {
          "param_id": 1,
          "per_value": "1 session",
          "body_part": "Hand"
          "icon": "http://localhost/botox/storage/web/source/1/c_8jYNk6egORdjAnTtadvDzQb4dhXZQf.png"
        },
        {
          "param_id": 2,
          "per_value": "2 sessions",
          "icon": "http://localhost/botox/storage/web/source/1/c_8jYNk6egORdjAnTtadvDzQb4dhXZQf.png"
        },
        {
          "param_id": 3,
          "per_value": "3 sessions",
          "icon": "http://localhost/botox/storage/web/source/1/c_8jYNk6egORdjAnTtadvDzQb4dhXZQf.png"
        }
      ]
    }
  ]
}
```


## Get treatments data [/get-data/treatment-data] [get]

Response: (This is only example!!!)

```
{
  "success": true,
  "data": [
    {
      "treatment_name": "Body Contouring",
      "treatment_id": 1,
      "sub_string": "Choose a location ant take photo",
      "instruction": null,
      "param_multiselect": 1,
      "select_both_button": 0,
      "buttons_in_row": null,
      "session_buttons_position": null,
      "icon_url": false,
      "params": [
        {
          "param_id": 1,
          "per_value": "1",
          "icon_url": false,
          "severity": [
            {
              "id": 1,
              "name": "Mild",
              "icon_url": "http://storage.botox.dev/cache/3/HbfxGvgVSnJfcHfhSyxBDP2tcOMA3QMx.png?w=200&s=32b885ae8ca96f7c4aaebe52cb73339b"
            },
            {
              "id": 2,
              "name": "Moderate",
              "icon_url": "http://storage.botox.dev/cache/3/HbfxGvgVSnJfcHfhSyxBDP2tcOMA3QMx.png?w=200&s=32b885ae8ca96f7c4aaebe52cb73339b"
            }
          ]
        }
      ],
      "sessions": [
        {
              "session_id": 1,
              "count": 1
            },
            {
              "session_id": 2,
              "count": 2
            },
            {
              "session_id": 3,
              "count": 3
         }
      ],
      "additional_attributes": [
        {
          "id": 1,
          "value": "Test"
        },
        {
          "id": 2,
          "value": "Test2"
        }
      ],
      "intensities": [
        {
          "id": 1,
          "brand": "Glycolic acid 70%",
          "intensity_name": "light"
        },
        {
          "id": 2,
          "brand": "ViPeel",
          "intensity_name": "medium"
        }
      ]
    }
  ]
}
```


## Inquiry (with treatment) create [/inquiry/create] [post]

Fields: 

```
type = 1 -- this is constant.
treatment_param_id = Column(Integer, nullable=False) -- get with method Get treatments data (can be array, if multiselect is true.)
severity_id = Column(Integer, nullable=True) if an array of treatment_params is passed: severity_id[treatment_param_id]
session_id = Column(Integer, nullable=false)
treatment_intensity_id = Column(Integer, nullable=True) if an array of treatment_params is passed: treatment_intensity_id[treatment_param_id]
```

Response:

```
{
  "success": true,
  "data": [
    "inquiry_id": 52 -- use this for getting list of offers in Get doctor inquiry list method.
  ]
}
```


## Inquiry (with brand) create [/inquiry/create] [post]

Fields: 

```
type = 2 -- this is constant.
brand_param_id = Column(Integer, nullable=False) -- get with method Get brands data (can be array, if multiselect is true.)
```

Response:

```
{
  "success": true,
  "data": [
    "inquiry_id": 52 -- use this for getting list of offers in Get doctor inquiry list method.
  ]
}
```


## Contact admin [/contact/send] [post]

Fields: 

```
subject = Column(String, nullable=False)
body = Column(String, nullable=False)
```

Response:

```
{
  "success": true,
  "data": "Thank you for contacting us. We will respond to you as soon as possible."
}
```


## Get profile of current user. [/user-profile/me] [get]

Response:

```
{
  "success": true,
  "data": {
    "firstname": "John",
    "lastname": "Doe",
    "gender": 1,
    "date_of_birth": "12/05/1993",
    "phone": "+1 310-307-9284",
    "address": "String Address",
    "state_id": 5,
    "city": "String City",
    "zipcode": "12321",
    "email": "testser@test.ru"
  }
}
```

## State notifications [/user-profile/update] [post]

Fields: 

```
state_notification = Column(Bool, nullable=True)
```

Response:

```
{
  "success": true,
  "data": {
    "firstname": "testasssss",
    "lastname": null,
    "gender": 1,
    "date_of_birth": "05/12/1993",
    "phone": null,
    "address": null,
    "state_id": 1,
    "city": "Odessa",
    "zipcode": "23532"
    "email": "testser@test.ru",
    "state_notification": "1"
  }
}
```


## Get inquiry history [/inquiry/history] [get]

Response:

```
{
  "success": true,
  "data": [
    {
      "inquiry_id": 52,
      "visit_date": 1457607121,
      "type": 2, -- type is constant (1 - treatment, 2 - brand),
      "procedure_name": "CoolSculpting"
    },
    {
      "inquiry_id": 53,
      "type": 1,
      "procedure_name": "Body Contouring"
    }
  ]
}
```


## Get unviewed doctor offers [/inquiry/get-new-offers] [post]

Response:

```
{
    "success": true
    "data": "2"
}
```



## Get Doctor Offers [/inquiry/get-doctor-offers] [post]

Fields: 

```
inquiry_id = Column(Integer, nullable=False)
doctor_id = Column(Integer, nullable=False)
```

Response (treatment):

```
{
  "success": true,
  "data": {
    "clinic": "John",
    "photo": "http://localhost/botox/storage/web/source/1/j4DnpszEy7epcUMf_N8QY0SRhbs7vLRG.png",
    "biography": "Some text",
    "address": {
      "zip_code": "12345",
      "state_id": 5,
      "city": "City Name",
      "address": "Address string"
    },
    "rating": {
      "stars": null,
      "reviews": null
    },
    "data": [
      {
        "procedure_name": "Chemical Peel",
        "param": "1 session",
        "price": "222",
        "param_name": "sessions",
        "amount": 3,
        "reward": 55.5
      },
      {
        "id": 25,
        "procedure_name": "Chemical Peel",
        "param": "3 sessions",
        "price": "123",
        "sessions": 0,
        "reward": 30.75
      }
    ]
  }
}
```

Response (brands) :

```
{
  "success": true,
  "data": {
    "firstname": "John",
    "lastname": "Doe",
    "photo": "http://localhost/botox/storage/web/source/1/j4DnpszEy7epcUMf_N8QY0SRhbs7vLRG.png",
    "address": {
      "zip_code": "12345",
      "state_id": 5,
      "city": "City Name",
      "address": "Address string"
    },
    "rating": {
      "stars": null,
      "reviews": null
    },
    "data": {
      "0": {
        "brand": "Belotero",
        "price": 144,
        "reward": 36,
        "param_name": "Vial",
        "param_value": "1"
      }
    }
  }
}
```

And if need sessions, response like this: 

```
{
  "success": true,
  "data": {
    "firstname": "John",
    "lastname": "Doe",
    "photo": "http://localhost/botox/storage/web/source/1/j4DnpszEy7epcUMf_N8QY0SRhbs7vLRG.png",
    "address": {
      "zip_code": "12345",
      "state_id": 5,
      "city": "City Name",
      "address": "Address string"
    },
    "rating": {
      "stars": null,
      "reviews": null
    },
    "data": {
      "0": {
        "brand": "Belotero",
        "price": 144,
        "reward": 36,
        "sessions": 1,
        "param_name": "Session",
        "param_value": "1 session"
      }
    }
  }
}
```