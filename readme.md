# Image Upload Service

## Prerequisites

- MySQL installed and running.
- Composer installed.
- Symfony server installed.

## Setup

1. Run `composer install` to install dependencies.
2. Start Symfony server by running:
   ```
   symfony server:start --no-tls -d
   ```
3. Create the database by running:
   ```
   php bin/console doctrine:database:create
   ```
4. Run migrations to set up the database schema:
   ```
   php bin/console doctrine:migrations:migrate
   ```
5. Navigate to [http://127.0.0.1:8000/](http://127.0.0.1:8000/) and verify the default Symfony page is displayed.

## Configuration

To switch between Azure and local storage:

- Edit the `.env` file and change the `APP_ENV` variable to `prod` for Azure storage or `dev` for local storage.

## Usage

### Postman:

- **Route**: `POST http://127.0.0.1:8000/image/upload/new`
- **Payload**: Form data with an image file attached. Use the key `image`.
- **Image Requirements**:
  - Width and height must not exceed 1024px.
  - Accepted formats: JPG or PNG.
- **Response**: 201 Created on success.
- Each upload creates a new row in the database.

### UI:

- Access the UI for uploading at [http://127.0.0.1:8000/image/view/](http://127.0.0.1:8000/image/view/).
- This UI calls the previously mentioned upload route.

## Design Considerations

- Utilizes a factory pattern to switch between Azure and local storage services at runtime.
- Image validation is done using constants and a private method.
- Data storage is handled using Doctrine entities and repositories.
- Security-sensitive information like BlobEndpoint is stored in the `.env` file.
- Future enhancements include improving UI design, adding custom exceptions, unit tests, and refactoring to extract image upload logic into a reusable class.
