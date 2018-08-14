RESTful web services support for files and images
=================================================
This module (restws_file) provides support for creating and updating fields of type file and
image.

It accepts (multiple) image/file data as base64_encoded strings in the
JSON payload.

Installation
------------
Requires RESTful web services (restws) enabled.

No additional steps needed, just enable the module in Drupal.

Usage example: posting images
-----------------------------
1. Enable and configure restws (and optionally restws_basic_auth), making sure
you can create/update a regular 'article' node.
2. Configure the 'article' stock content type of your Drupal installation, to
have an image field called "field_image". For the sake of the example, don't
configure it as multi-value just yet (see below).
3. Using any REST client extension, POST a JSON body payload like the one in
examples/image-payload.json.
4. Check the newly created article: it has an image!.

Works with files too
--------------------
You can work with files as well as with images. Let's try posting a file to a file field:
1. Create another field named "field_txtfile", of type file (again, don't make it multi-value, see below)
1. Using any REST client extension, POST a JSON body payload like the one in
examples/file-payload.json.
1. Check the newly created article: it should now have a text file!.

Create, Retrieve, Update, Delete (CRUD)
---------------------------------------
We've just seen how to create files and images in the above examples.

To retrieve the information in a RESTful way, please check out the parent module [restws](https://www.drupal.org/project/restws).

As for updates, you can issue a PUT request to modify any entity given its ID. You could certainly use the ID returned in a previous POST request. Just remember to SKIP setting a type, as the entity already knows its type.

Drupal will change any value of the entity you send in the request. Now, if you want to modify the contents of the image or file field, you can either
1. send the new base64 string/s (as in the POST examples)
2. replace a file/image by changing the fid to an existing one, for example, by issuing a PUT message to the desired node:
    ```
    {
      "field_image": { "fid": "140117" }
    }
    ```

Last but not least, you can delete any entity by issuing a DELETE request with only the ID you want to delete.

Multi-value field
-----------------
If field_image is configured as multi-value, the JSON payload should be an
array with this structure:

```
  "field_image": [
    base64_encoding_0,
    base64_encoding_1,
    ...,
    base64_encoding_n
  ]
```

This makes it possible to upload n images in a single POST request.

Credits
-------
Mariano E. Barcia Calderón (mbarcia@asmws.com)

Originally started from [this patch](https://www.drupal.org/node/2320083).
