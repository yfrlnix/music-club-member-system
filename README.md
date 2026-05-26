# Hands-On: Profile Picture & Paging

A CodeIgniter 4 application demonstrating profile picture upload, paginated user listing, edit and delete functionality.

---

## Activity Objective

Build a user profile system with photo upload, paginated user listing (5 users per page), edit and delete records.

---

## Features Implemented

- Add Member – Register new members with profile photo upload
- File Upload Field – Avatar/profile picture upload in user profile view
- Image Validation – Validates file type (image), MIME type, and file size
- File Storage – Moves uploaded files to `public/uploads/avatars` directory
- Database Storage – Stores image path in database
- Pagination – Displays 5 users per page with navigation links
- Search Filtering – Optional search functionality for filtering users
- Edit Member – Update member information and change profile photo
- Delete Member – Remove member records from database (with photo deletion)

---

## Technologies Used

- CodeIgniter 4 (PHP Framework)
- MySQL (Database)
- HTML/CSS/Bootstrap
- XAMPP (Local Server)

---

## Project Structure

```text
app/
├── Controllers/
│   └── Members.php
├── Models/
│   └── MemberModel.php
├── Views/
│   ├── layouts/
│   │   └── main.php
│   └── members/
│       ├── form.php
│       ├── index.php
│       └── show.php
└── Config/
    └── (configuration files)

public/
├── css/
│   └── app.css
└── uploads/
    └── avatar/
        └── (stored profile images)
```

---

## How It Works

### 1. Add Member

Fill out the form and upload a profile picture.

- System validates if the uploaded file is a real image
- Checks file type and file size limit
- Saves image inside `public/uploads/avatars`
- Stores image path in the database

---

### 2. View Members

Displays 5 members per page.

- Pagination links for navigation
- Profile pictures displayed beside member information
- Edit and Delete buttons available for each member

---

### 3. Search Members

Filter members by username.

- Search results also support pagination

---

### 4. Edit Member

Update existing member information.

- Form is pre-filled with existing data
- User can upload a new profile photo
- Old photo is automatically removed
- Existing photo remains if no new file is uploaded

---

### 5. Delete Member

Remove a member permanently.

- Deletes profile photo from uploads folder
- Removes database record
- Includes confirmation prompt before deletion

---

## CRUD Operations Completed

- **Create** – Add new member with photo upload
- **Read** – View member list with pagination and search
- **Update** – Edit member information and profile picture
- **Delete** – Remove member and associated image

---

## Validation Rules Implemented

- File must be an image (`jpg`, `jpeg`, `png`, `gif`)
- Maximum file size: 2MB
- Required form field validation
- Name validation
- Email format validation (if applicable)

---

### Screenshots Included

- Add member form with upload field
- Paginated member listing with edit/delete buttons
- Edit member form with existing data
- Search functionality

---

## Author

**April Nicole Custorio**

---

## Year/Block

**3RD/3.2-BSIT**
