# Image Upload Service

## Prerequisites

- MySQL installed and running.
- Composer installed.
- Symfony cli installed.

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

- I wanted to use the same route for both Azure and Local storage. I’m written both services to implement the same interface, and then used the factory pattern to return the one I want at runtime. This makes it a lot cleaner and easier to extend, e.g. If we wanted an s3 integration too.  
- We’re just using an env variable here, so there’s no need to set a query param in the request which would have been another option. 
- The image itself is validated in a private method. Nothing crazy but good to use consts incase the requirements change and we have ‘magic’ numbers in our code. There are probably libraries that will validate the image in a nicer way. 
- I’m just saving into the db using an Entity with Doctrine mappings. Again, nothing too crazy as there’s only one entity so no relations etc. I’ve made a Repository for it too - I’m a fan of the Repository pattern even though I haven’t used it here
- BlobEndpoint gets kept in an .env file for security purposes.

## Future work

- Make the ui looks a lot nicer 
- Make some custom Exceptions. These make debugging a lot easier. 
- Write unit tests
- Extract image upload code into an ImageUploader class. Depending on what application we are building we can reuse in other parts, e.g. other controllers, services

