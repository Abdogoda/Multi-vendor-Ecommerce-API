
# 📦 Laravel Multi-Vendor eCommerce API

Welcome to the **Laravel Multi-Vendor eCommerce API** project! This is a robust and scalable API designed for a multi-vendor eCommerce platform built with Laravel. Whether you're a developer or a tech enthusiast, this README will guide you through understanding and getting started with the project.

## 🚀 Overview

This project provides a comprehensive API for a multi-vendor eCommerce platform, allowing multiple vendors to list products, manage orders, and handle payments. Customers can browse products, place orders, and manage their profiles.

## 🛠 Features

- **Authentication**: Utilized OAuth JWT for secure authentication, and integrated Google OAuth for seamless login and registration.
- **Multi-Role Authentication**: Facilitated distinct access levels for customers, admins, and vendors.
- **Payment Integration**: Implemented Paymob for secure payment processing.
- **OTP Verification**: Enabled OTP for account verification, password reset, and security checks.
- **Caching: Employed** caching to enhance data retrieval performance and reduce server load.
- **Job Scheduling**: Implemented job queues for efficient email dispatch and execution of time-consuming tasks.
- **Multi-Language Support**: Supported both Arabic and English for data handling, user interface, and error messages.
- **Email Management**: Configured SMTP for sending well-structured, formatted emails to users.
- **Vendor Management**: Manage vendor profiles, products, and payment methods.
- **Product Management**: CRUD operations for products, including product images and tags.
- **Order Management**: Handle customer orders, including checkout and payment processes.
- **Category and Tag Management**: Manage product categories and tags.

## 📜 Technologies Used

- **Laravel**: The PHP framework used for building the API.
- **MySQL**: Database management system.
- **JWT Authentication**: For secure API access.
- **Google OAuth**: For social login integration.
- **Payment 3rd Party Integration**: For complete order payment via (Visa Card | Online Card | Mobile Wallet).

## 🗂 Database Schema

The database consists of the following tables:

- **Users**: `email`, `phone`, `first_name`, `last_name`, `password`, `address`, `profile_image`, `role`, `status`
- **Vendors**: `shop_email`, `shop_phone`, `shop_name_en`, `shop_name_ar`, `shop_address`, `shop_logo`, `description`
- **Categories**: `name_en`, `name_ar`, `slug`, `description`, `icon`, `image`, `parent_id`
- **Tags**: `name_en`, `name_ar`
- **Products**: `name_en`, `name_ar`, `main_image`, `price`, `stock`, `description`, `category_id`, `vendor_id`, `status`
- **Product Images**: `product_id`, `image`
- **Product Tag**: `product_id`, `tag_id`
- **Payment Methods**: `name`, `description`, `status`, `icon`
- **Vendor Payment Method**: `vendor_id`, `payment_method_id`, `identifier`, `integration_id`
- **Orders**: `user_id`, `payment_method_id`, `total_amount`, `order_date`, `shipping_address`
- **Order Products**: `order_id`, `product_id`, `price`, `quantity`

## 🔗 API Routes

### Auth Routes

- **Guest Routes**:
  - `POST /auth/login`: Login
  - `POST /auth/register/customer`: Register Customer
  - `POST /auth/register/vendor`: Register Vendor
  - `POST /auth/password/forgot`: Forgot Password
  - `POST /auth/password/reset`: Reset Password
  - `POST /auth/otp/send`: Send OTP
  - `POST /auth/otp/verify`: Verify OTP
  - `GET /auth/google/redirect`: Google Login
  - `GET /auth/google/callback`: Google Callback

- **Authenticated Routes**:
  - `POST /auth/refresh-token`: Refresh Token
  - `POST /auth/logout`: Logout
  - `GET /auth/profile`: View Profile
  - `POST /auth/profile`: Update Profile
  - `PATCH /auth/profile/vendor`: Update Vendor Profile
  - `POST /auth/password/change`: Change Password
  - `POST /auth/users/{user}/activate-account`: Activate Account (Admin Only)

### Customer Routes

- `GET /customers`: List Customers
- `GET /customers/{customer}`: View Customer Details

### Customer Order Routes

- `GET /customer/orders/{order}/checkout`: Checkout
- `GET /customer/orders/payment/callback`: Payment Callback
- `GET /customer/orders`: List Orders
- `POST /customer/orders`: Create Order
- `GET /customer/orders/{order}`: View Order Details
- `PATCH /customer/orders/{order}`: Update Order
- `DELETE /customer/orders/{order}`: Delete Order

### Vendor Routes

- `GET /vendors`: List Vendors
- `GET /vendors/{vendor}`: View Vendor Details
- `GET /vendors/{vendor}/products`: List Vendor Products
- `GET /vendors/{vendor}/payment_methods`: List Vendor Payment Methods

### Payment Methods Routes

- `GET /payment_methods`: List Payment Methods
- `GET /payment_methods/{paymentMethod}`: View Payment Method Details
- `POST /payment_methods`: Create Payment Method (Admin Only)
- `PATCH /payment_methods/{paymentMethod}`: Update Payment Method (Admin Only)
- `DELETE /payment_methods/{paymentMethod}`: Delete Payment Method (Admin Only)

### Vendor Payment Methods Routes

- `POST /vendor-profile/payment_methods`: Manage Vendor Payment Methods
- `POST /vendor-profile/orders`: Manage Vendor Orders

### Tag Routes

- `GET /tags`: List Tags
- `GET /tags/{tag}`: View Tag Details
- `POST /tags`: Create Tag (Admin/Vendor Only)
- `PATCH /tags/{tag}`: Update Tag (Admin Only)
- `DELETE /tags/{tag}`: Delete Tag (Admin Only)

### Category Routes

- `GET /categories`: List Categories
- `GET /categories/{category}`: View Category Details
- `POST /categories`: Create Category (Vendor/Admin Only)
- `PATCH /categories/{category}`: Update Category (Admin Only)
- `DELETE /categories/{category}`: Delete Category (Admin Only)

### Product Routes

- `GET /products`: List Products
- `GET /products/{product}`: View Product Details
- `POST /products`: Create Product (Vendor Only)
- `PATCH /products/{product}`: Update Product (Vendor Only)
- `DELETE /products/{product}`: Delete Product (Vendor Only)
- `POST /products/{product}/images/add`: Add Product Images
- `POST /products/{product}/images/delete`: Delete Product Images
- `POST /products/{product}/tags/update`: Update Product Tags

### Admin Routes

- `GET /admins`: List Admins
- `GET /admins/{admin}`: View Admin Details
- `POST /admins`: Create Admin

## 🛠 Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/yourusername/your-repository.git
   ```

2. **Navigate to the Project Directory:**

   ```bash
   cd your-repository
   ```

3. **Install Dependencies:**

   ```bash
   composer install
   ```

4. **Set Up Environment File:**

   Copy `.env.example` to `.env` and configure your database and other environment variables.

   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key:**

   ```bash
   php artisan key:generate
   ```

6. **Run Migrations:**

   ```bash
   php artisan migrate
   ```

7. **Start the Development Server:**

   ```bash
   php artisan serve
   ```

## 📚 Usage

You can now use the API endpoints as described in the routes section. Make sure to include appropriate headers for authentication and content type in your requests.

## 📸🖼️ Swagger Documentation
![Screenshot 2024-09-14 025119](https://github.com/user-attachments/assets/33d76555-eaf1-4288-8557-e5943a2aef7e)
![Screenshot 2024-09-14 025331](https://github.com/user-attachments/assets/afd036e4-955b-41a2-bb9e-90b94e506494)
![Screenshot 2024-09-14 025232](https://github.com/user-attachments/assets/4b45920a-6978-47cd-ac8c-b486d80dc88a)
![Screenshot 2024-09-14 025210](https://github.com/user-attachments/assets/8b00b2e3-7793-4689-953d-5a94d4d5030c)
![Screenshot 2024-09-14 025150](https://github.com/user-attachments/assets/2648434a-a8e0-43de-99b9-6ffb52fa6e2b)

To provide a comprehensive overview of the API endpoints and their functionalities, we use Swagger for API documentation. Below are some screenshots of the Swagger documentation:


For a live view of the Swagger documentation, you can access it at `http://localhost:8000/api/v1/docs` after starting the Laravel server.

## 💡 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or fix.
3. Make your changes and test thoroughly.
4. Submit a pull request with a clear description of your changes.

## 📜 License

This project is licensed under the [MIT License](LICENSE).

## 📬 Contact

For questions or support, please reach out to [abdogoda0a@gmail.com](mailto:abdogoda0a@gmail.com).

## 🙏 Acknowledgements

- [Laravel](https://laravel.com/) for the amazing framework.
- [Google OAuth](https://developers.google.com/identity) for authentication.
