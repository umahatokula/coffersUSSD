
# USSD Power Purchase App

## Introduction
This USSD Power Purchase App, developed using Laravel, is designed to facilitate the purchase of power from a vendor through the use of USSD technology. The app provides a seamless and efficient way for users to buy power directly from their mobile devices. This README provides an overview of the USSD Power Purchase App and its features.

## Features
1. Power Purchase:
   - Users can easily purchase power by dialing a USSD code on their mobile devices.
   - The app guides users through the power purchase process, providing a user-friendly interface for a smooth experience.
   - Users can select the desired amount of power to purchase and confirm the transaction.


## Installation and Setup
To set up the USSD Power Purchase App locally, follow these steps:

1. Clone the repository from the GitHub repository: [link-to-repository](https://github.com/umahatokula/coffersUSSD).

2. Navigate to the project directory:
   ```
   cd coffersUSSD
   ```

3. Install the required dependencies using Composer:
   ```
   composer install
   ```

4. Create a copy of the `.env.example` file and name it `.env`:
   ```
   cp .env.example .env
   ```

5. Generate a new application key:
   ```
   php artisan key:generate
   ```

6. Configure the database connection and other necessary settings in the `.env` file.

7. Run the database migrations:
   ```
   php artisan migrate
   ```

8. Start the local development server:
   ```
   php artisan serve
   ```

9. Access the application in your web browser or through a USSD-compatible mobile device.

## Dependencies
The USSD Power Purchase App is built using the Laravel framework and relies on the following dependencies:

- Laravel 8.x
- PHP 7.4 or higher
- MySQL or another compatible database system
- Composer (PHP package manager)
- Additional dependencies specified in the `composer.json` file

Ensure that these dependencies are installed and properly configured before running the application.

## Contributing
Contributions to the USSD Power Purchase App are welcome. If you have suggestions, improvements, or bug fixes, please submit a pull request on the GitHub repository. Follow the existing coding style and include relevant tests for your changes.

## License
The USSD Power Purchase App is open-source software released under the [MIT License](https://opensource.org/licenses/MIT). Feel free to use, modify, and distribute the application in accordance with the terms of the license.

## Contact
For any inquiries or support related to the USSD Power Purchase App, please contact the developers at [support@koachtech.com](mailto:support@koachtech.com).

Thank you for using the USSD Power Purchase App. We hope this application provides a convenient and efficient solution for power purchases through USSD technology.
