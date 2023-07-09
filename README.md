# Laravel Code challenge

## Description

We need a product review system with the following features:

1. Each user can add comments to a product by sending the product name and comment
2. Products and the number of comments must be stored in the following file:

-   "/opt/myprogram/product_comments"

3. Products and the number of comments must be in the following format:

-   Product name: Total number of comments

4. Adding new comments for a product must increase the total number of comments on that product
5. If the product name does not exist in the system, that product will be added to the system
6. Each user can register a maximum of 2 comments for each product The following functionalities should be provided via the API.

-   Sign up
-   A command to insert new products
-   API to add comments to products
-   API to get products list and corresponding comments
-

### Notes

-   To update comments count, the entire contents of the file should not be re-created and only the desired product row should be updated
-   Do not use any packages and UPS code to update the file, please update the file using Linux commands. (such as exec())

### Requirements

-   Use PHP Laravel framework
-   Use MySQL database
-   Authenticate via JWT token
-   Add below products by default: A, B, C
-   Please note that all the endpoints should be secure and immune to code-injection and all the inputs must be

### properly validated

-   Please use one Creational and Structural design pattern
-   Please use Dependency Injection

### Delivery

-   Please send us the postman collection Plus Points
-   Having tests for endpoints
-   Using PSR5

# result

-   scribe for document
