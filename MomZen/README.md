1- gestion reclamation (fiha 7aja na9sa(integration de template ne9sas)+controle de saisie)
2- CRUD blog (article) + interface liste blogs(client) + controle de saisie
3- an admin can answer to a reclamation through reponsse entity, then the reclamation status is changed to answered
4- CRUD reponse entity for admin
5- formulaire de reclamation pour client(interface deja existante)

## Blog Routes

### Public Routes
- `GET /blog/` - View all published articles
- `GET /blog/{id}` - View a specific article

### Admin Routes
- `GET /blog/admin` - Admin dashboard for articles
- `GET /blog/admin/new` - Create new article form
- `POST /blog/admin/new` - Handle new article creation
- `GET /blog/admin/{id}/edit` - Edit article form
- `POST /blog/admin/{id}/edit` - Handle article update
- `POST /blog/admin/{id}` - Delete article

Each admin route requires ROLE_ADMIN access.

## Reclamation Routes

### Public Routes
- `GET /reclamation/` - View user's reclamations
- `GET /reclamation/new` - Create new reclamation form
- `POST /reclamation/new` - Handle new reclamation creation
- `GET /reclamation/{id}` - View a specific reclamation
- `POST /reclamation/{id}` - Delete a reclamation (only if pending)

### Admin Routes
- `GET /reclamation/admin` - Admin dashboard for reclamations
- `GET /reclamation/admin/{id}` - View reclamation details in admin panel

## Response Routes

### Admin Routes
- `GET /admin/response/{reclamationId}/new` - Create new response form
- `POST /admin/response/{reclamationId}/new` - Handle new response creation
- `GET /admin/response/{id}/edit` - Edit response form
- `POST /admin/response/{id}/edit` - Handle response update
- `POST /admin/response/{id}` - Delete response

All response routes require ROLE_ADMIN access.
