#Standards
##Colors
* Inactive border: border-gray-200
* Active border: border-indigo-400 text-white
* Active element: bg-indigo-400 text-white 
* Hover background: bg-gold-100
* Text: text-gray-800
* Primary color: indigo-500
* Secondary color: gray-500
* Danger color: red-500
* Info background color: blue-100
* Info icon color: blue-500
* Warning color: yellow-500
* Success color: green-100
* OK color: green-500

#To do (v2020.2)
###Clients
###Core
1. Repository `findBy` methods need to distinguish between find first and find
all.
1. Intercept 412 responses and fetch the desired object first rejecting the
original alteration request with a generic message: "Object was externally
modified, so your changes could not be applied. The modifications were loaded
from the API, and should be visible."
1. Support setting moment locale based on Laravel / browser locale.
###DateTimePicker
###FilteringDropdownControl
###General
1. Upgrade to Laravel 7.
1. Break-up Project-Management.tech into several packages:
   + Core: Have the basic interface definitions, and things like tailwind
   + i12: Iso related information, services and tables.
   + Project: Deal with all project related stuff
   + Public-Web: Have the public facing parts of the website
   + Task: Deal with all task related stuff
   + Timesheet: Deal with all timesheet related stuff
   + UI: Deal with general UI things
   + Workspace: Deal with all workspace related stuff
###Projects
1. Write tests around ProjectRepository
1. Delete Project in SPA
1. Archive Project in SPA
1. Update Project in SPA
1. Write tests around Project API
1. Close project. This stops timesheet entries being created against those
projects (even historic timesheet entries cannot be started against closed
projects).
###Tasks
###Timesheets
1. Make timesheet report graphs responsive (including ticks on X axis)
###UI/UX
1. Make `PmtechInput` a global component
1. Add FlashMessage component of some variety to show info, warning, success and
global error messages.
1. Update window title with `title` from routes in the afterEach guard
###Users
1. Allow user to request another activiation link
1. Allow user to set for which events an email should be sent.
1. Remove an account completely with an option to download all data and files
for all projects etc. that would have been stored in the any of the owned
workspaces.
1. Complement User tests with the Settings stuff
1. Delete settings when User model is deleted
###Workspaces
1. Add full member profile in order to understand email domain and country
information for each user / subscriber. This can be used to determine the amount
of users in a workspace and to determine whether or not VAT is to be applied to
the billable amount.
1. Make sure WorkspaceUpdated events are send (via Pusher) to web clients
listening on the Workspace private channel. Make sure to have this covered in
test cases.
1. Complete WorkspacePolicy with reference to all Business Requirements
1. Write WorkspaceController actions
1. Create routes for workspace things - CRUD actions for API

#To do (v2020.3)
###Clients
###Core
###General
###Navigation
###Projects
1. Add `price` attribute for projects
1. Add `reduction_percentage` to Project
1. When adding price to project, show estimate based on project tasks and
profit_margin.
1. Allow project member to overwrite `cost` from workspace member
1. Allow project to overwrite `profit_margin` from workspace
###Tasks
1. Add `percent_completion` to `Task`
1. Add Completion field to GanttChart's GridTable
1. Add Completion field to TaskProperties (if all tasks are at 100%, show a
checked checkbox, otherwise show a number input min 0 max 100). If not all tasks
have the same percent_completion value, do not allow editing of this field.
1. Add `nesting_level` and `parent_task_id` to Task model
1. Add actual `duration`, `ended_at`, `started_at` and `work` to Task model
1. Add planned `duration`, `ended_at`, `started_at` and `work` to Task model
1. ForceDelete Task in SPA only if task has no timesheet entries
1. Reorder tasks (via buttons in toolbar)
1. Send TaskUpdated event when task was updated
1. When task is work-driven, update % complete according to approved timesheet
records
1. Mark task as 100% or completed. This stops timesheet entries being created
against those tasks (even historic timesheet entries cannot be started against
completed tasks).
###Timesheets
###UI/UX
###Users
###Workspace
1. Add `cost` per workspace member
1. Add `profit_margin` to workspace

#To do (v2021.1)

#To do (unassigned to release)
1. When user accepted invitation to join or was added to a workspace, the
workspace owner should be notified that the user now needs access to projects
in order to collaborate with other project members.
1. Bruteforce POST login protection - Make sure that users cannot fail login
attempts more than 5 times in 5 seconds
1. Add Brute Force protection around registration route as well.
1. Submit weekly timesheet
1. Lock timesheet entries after submitting
1. Add hourly cost to workspace or project members
1. Add type to project - hourly, fixed, retainer
1. Currency per workspace and apply xe exchange rate between project and
workspace defined in the workspace yet
1. Unit test NotificationRepository
1. Write Notification routes and Controller methods
1. Unit test Notification API
1. Create Notification controller, migration and model
1. Write NotificationRepository
1. Project Dashboard to list all tasks' main details (name) and total hours
recorded against each task
1. Add Currency model / migration
1. Link `Workspace` and `Project` to `Currency`
1. Automatically convert to workspace currency any project that has financial
details in a different currency (payable and submitted invoices). Use the
package mentioned in Laravel News "Laravel Exchange Rates is a package by Ash
Allen for interacting with the exchangeratesapi.io API.".  
1. Implement logic in Login SPA Component to go to the back URL
1. Listen to private broadcast channel for each project in Vuex
1. GanttChart should only update API if the task was truly altered
1. Global "loading" div with incrementLoading and decrementLoading vuex
committers
1. Replace App.vue `loading` with a full modal div that shows whenever there are
Axios requests in progress for longer than 2 seconds
1. `GridTable` to support `sortable` flag in field definition and sorting by
multiple `sortable` fields
1. `GridTable` to support notion of visible and hidden columns. Right click
shows a popup menu where one can select to make hidden columns visible; also one
can make visible columns hidden
1. **BR000003** - Add project to workspace should send Notification to Workspace
Owner
1. **BR000004** - Even though tasks can be archived or deleted, this cannot
happen when there are timesheet entries against a task.
1. **BR000005** - Even though projects can be archived or deleted, this cannot
happen when there are timesheet entries against a project.
1. **BR000010** - Users with the role "Line Manager" or "Project Manager" can
approve submitted timesheets
1. **BR000011** - Approved timesheet entries can only be archived by users  with
the role "Programme Manager" or "Portfolio Manager"
1. **BR000012** - Lock timesheet entries after submitting so that they cannot be
edited by the user, but only approved / rejected by the respective Manager users
1. **BR000014** - Write cypress tests to make sure that the correct confirmation
messages are shown.

#To do (Good first issue)

#In Progress
1. Rewrite repositories to implement a common interface and derive from a
common parent class

#Done
1. **BR000001** - Setup a "Default" workspace when a user registers
1. **BR000002** - Allow AccountActivation email to be regenerated upon user
request
1. **BR000006** - Any project_user can create a timesheet entry against any task
in the project or against the project it self
1. **BR000007** - Any workspace_user can create a timesheet entry against that
workspace. 
1. **BR000008** - TimesheetEntries can be created against a project as such or a
task or simply in the workspace
1. **BR000009** - Users can edit only their own timesheet entries
1. **BR000013** - Workspace names should be unique for the ownerUser
1. **BR000014** - If a user deletes / archives his / her last owned workspace,
warn the user specifically that that is the last owned workspace. The user can
always create a new workspace any time (s)he wants.
1. **BR000015** - Client name must unique inside a workspace
1. **BR000016** - `TimesheetEntry`.`started_at` must be before
`TimesheetEntry`.`ended_at`
1. **BR000017** - Two timesheet entries for the same user (regardless of
workspace / project / task) may not overlap.
1. **BR000018** - When POSTing a new timesheet entry, any still running
timesheet entries must be stopped by setting their `ended_at` attribute to one
second before the new entry's `started_at` attribute.
1. **BR000019** - Timesheet entries can only be created through the API when a
user is logged in.
1. **BR000020** - A user must have at least 1 owned Workspace at all times,
unless the user is deleting its account.
1. **BR000021** - Only workspace users can retrieve workspace details from the
API
1. **BR000022** - Project names must be unique within a workspace
1. Create workspaces table - id (uuid), owner_user_id, name
1. Create Workspace model
1. Create relationship between User and Workspace models
1. Create WorkspaceRepository - CRUD actions
1. Write tests around the WorkspaceRepository
1. Create TE Seeder
1. Hide id, user_id, in Settings
1. User model $with settings
1. Create a user Settings Model and table (id (int), user_id, last_visited_view)
1. Create settings record when a user is created.
1. Create SettingsRepository
1. Create command to create user settings if the user doesn't have it yet
1. Login via the SPA - Login screen, logic, and redirect
1. Make sure browser refresh (F5) works with Laravel routing into SPA
1. Allow user to logout from SPA
1. Allow `last_visited_view` update in Settings through SettingsController
1. Add Project model / controller / migration
1. projects table has workspace_id, color, name
1. Implement relationships in Project model
1. Write ProjectRepository
1. Project API list projects for workspace
1. Project API add project to workspace
1. Load projects from API when changing into new workspaces router view
1. WorkspaceDashboard should display a call to action if there are no projects
1. Create new Project in SPA
1. Add "color" and "id" to the visible properties of the Project model
1. Workspace Dashboard should list each project's main details and show an "Add
project" button
1. Send WorkspaceUpdated notification via Pusher when project is added
1. Add vue-router links for projects (nested under workspaces)
1. Add Task model / controller / migration
1. Write TaskRepository
1. Write tests around TaskRepository
1. Allow task index to be paginated
1. Create new Task in SPA
1. Order tasks in the index by their `wbs` by default
1. Add TimesheetEntry model / controller / migration
1. Write TimesheetEntryRepository
1. Write tests around TimesheetEntryRepository
1. Write TimesheetEntry API and corresponding tests
1. Create timesheet entry editor form
1. Write Authorization policy around timesheet entries
1. Display 5 days worth of timesheet entries in the TimesheetEditor
1. Create user_workspace and project_user
1. Implement Project Selector for Timesheet Entry Editor
1. The TimesheetEntryEditor should display the correct project details
1. Implement Task Selector for Timesheet Entry Editor
1. Show duration for Timesheet Entry Editors that are not running a new entry
1. Start new timesheet entry from history
1. Allow Timesheet Index By Day to be collapsed / expanded
1. Style Timesheet Entry Editor
1. Style Timesheet Index By Day
1. Update API as and when "description" is updated in `TimesheetEntryEditor`
with a delay of 2 seconds.
1. Updates in any input in the Timesheet Entry Editor should result in a PUT
request to update the API
1. Style navigation horizontall on the top , but make it responsive
1. Add dropdown menu "Timesheets" into menu bar (see https://tailwindcss.com/course/making-the-dropdown-interactive/)
1. `FilteringDropdownControl` should highlight (`bg-indigo-400`) the selected
entry and scroll it into view, whilst keeping the down and up keys working
1. Disable `TimesheetEntryStartButton` when the description is empty
1. `TimesheetEntryEditor` needs to change between `TimesheetEntryStartButton`
and (new) `TimesheetManualSaveButton` components
1. Implement Date/Time picker
1. Use `DateTimePicker` in `TimesheetManualSaveButton`
1. Use `DateTimePicker` in the `TimesheetEntryStartButton` to display historical
`started_at` time
1. Use `DateTimePicker` in the `TimesheetEntryEditor` for setting a new
`started_at` time
1. Style the running timesheet entry a bit better and include a `DateTimePicker`
to reset the start time.
1. Instead using a global onBlur event, use backdrops to capture "outside" click
events for any element that has a popup (see https://tailwindcss.com/course/making-the-dropdown-interactive/
at about 04:40). See also `TimesheetDropdownNavItem`.
1. Migrate to Laravel Airlock
1. When a user creates a new project, that user should be associated with that
project in project_user
1. Add Logout to person's dropdown menu on the far right...
1. When logging out, the authenticated Vuex state needs to change
1. Generate timesheet report per workspace. Drill down per user, project or task
1. When clicking the start button, after selecting project & task, the selection
for project and task disappears
1. When adding a project, the vuex store needs to reload its projects (through a
Workspace Update Notification via Pusher?)
1. Export timesheet report
1. Style input boxes according to
https://codesandbox.io/s/vue-template-lldw2?from-embed
1. Implement a generic error handler (also in the API)
1. Style and implement front-end
1. Style back-end
1. Make sure Laravel Airlock can authorize broadcasting private channel access
1. Let the user create a new workspace in SPA
1. Let the user switch between workspaces
1. Update Task in SPA
1. Look and feel of activation email - Just verify it, modify it if needed -
Depends on the look and feel of the main site... Currently left blank without
styles, really.
1. Start writing e2e UI tests using cypress.io
1. Migrate data from current www.project-management.tech to new
www.project-management.tech implementation
1. Let the user archive a workspace in SPA
1. Let the user delete a workspace in SPA
1. Let the user edit the workspace name in SPA
1. Invite users (new and existing) to the workspace
1. Let the user assign ownership of a workspace to another user
1. When the workspace has more than 5 members the owner needs to pay
1. When an email address registers again, but was not verified, send the whole
verification email again (also create a cache entry).
1. When an email address registers again, and it was verified before, the
request should send an *Unauthorized.* response.
1. Add Client model / migration / controller / repository
1. Allow user to add client in SPA
1. Projects can be associated with a client
1. Add project allows user to pick a client
1. Timesheet report can be drilled down by Client
1. Support i18n in terms of month and day names
1. Support Clicking Year to show a list of 10 decades (based on the current
Year). Each decade can be clicked to pick a specific year in that decade
1. Support Clicking Month to show a list of months that can be picked
1. Support Clicking Hour to show a list of all hours that can be picked
1. Support Clicking Minute / Second to show a list of 12 minutes / seconds (5
minutes / seconds between each, i.e. 0 5 10 15 etc) that can be picker
1. Support proper config merging (`defaultConfig` in `data`, `passedConfig` in
`props` and a new, merged, `config` in `data` which is passed on to
subcomponents)
1. Rename `FilteringDropdownControl` to `ComboControl`
1. Support native hover events to recalculate `highlightedEntryIdx` and
`highlightedEntryId` based on those hover events
1. Receive a lot of 419 errors after 1 hour of not interacting with the site.
Implement silent keep-alive or increase lifetime of session / Airlock cookie.
1. Allow user to switch workspaces in xs / sm screens
1. Add `abbreviation` and `start_date` attributes to project Model
1. Show a small `TimesheetEntryEditor` in the bottom right hand corner when
the user has a timesheet entry started **and** is not visiting the timesheet
editor.
1. Make sure the ComboControl filtering matches case-insensitively a regex,
rather than just the start
1. Remove members from workspace
1. Create simple analytics where we store only the user agent and screen size
information in the database.
1. Implement SettingsRepository and use it in SettingsController
1. Write tests around SettingsRepository and SettingsController
1. UserRepository should not create Settings object. That has to be done in a
UserObserver class.
1. Add dropdown menu "Projects" into menu bar (see https://tailwindcss.com/course/making-the-dropdown-interactive/)
1. Order projects in the index by their `name` by default
1. Allow project index to be ordered by name, progress, etc.
1. Add test that Settings are created when account was verified
1. Add E-Tag support
   1. In middleware to verify against put requests
   1. In middleware to add to the response headers
   1. In models being updated out of the Vuex Store
1. Complete CRUD actions in UserRepository - And write corresponding test cases
1. `GridTable` to support clicking on row headers to select entire row. Multiple
selections are possible through use of Shift-Click and Ctrl-Click
1. Add toolbar with task and project related buttons just above the Gantt Chart 
1. Allow task to be work-driven
1. GridTextEditor needs to send input updates more frequently than onBlur
1. `GridTable` needs `GridPercentageEditor`
1. Protect tasks with e-tags (so that multiple clients can view tasks...)
1. Listen to private broadcast channel for each task in Vuex
1. `WorkspaceRepository::get` should be allowed to return NULL
1. Project and Workspace `id` cannot be updated through their respective
repositories

#Details
##BR000001
The workspace should only be created when the user verifies the email address.
##Login via the SPA - Login screen, logic, and redirect
Correct login works and we can redirect already. Needs tweaking to correct URL
(/members/workspaces/:id), so we need to verify the user profile settings for
the last visited Vue view, and if none, we need to check the list of workspaces
to go to the *one* workspace, if not -> we simply go to the first workspace
in the list.
##Send WorkspaceUpdated notification via Pusher
Only send the id of the affected Workspace. Load the workspace from the API. If
it is the current workspace, also dispatch "workspaceChanged".
##ETag support
Supporting ETags on API endpoints is fairly straightforward: grab the response
from the request pipeline, and calculate some kind of strong hash (eg. `sha1`).
Compare that value against the value of the request header If-None-Match and
send appropriate back to the API caller. This is what Laravel's built-in
Cache-Control (via `cache.headers` middleware) supports.

However, if one wants to store a number of Laravel Models in a Vuex store for
easy access and retrieval without the need to keep going back to the API to
retrieve these models a number of times, we need some mechanism to retrieve an
ETag for each individual model in that index.

There's two ways to go about it:
1. Only send IDs as index response, and let the SPA fire off a number of GET 
   requests as necessary to retrieve each Model's ETag.
2. Calculate ETag hash values on individual objects.

Option 1. has the potential to violate Laravel's throttle middleware, and thus
each of those subsequent GET requests would need to be fired off at a rate of
one request per second. This is not practicle in large-ish applications.

Option 2. can be implemented in various ways. I've looked at:
1. jsonSerialization overload
2. Response Facade extension
3. API Middleware

Option 1. would work in terms of generating the ETag for each individual object,
it would be called any time the API wants a serialized view of the model. This
would open a potentially catastrophic amount of hash calculation that may slow 
the API right down. Also, it is not entirely clear how an index API call would
provide back all the individual ETags.

Option 2. would work as well, and is, in fact, proposed by @fideloper, see
https://fideloper.com/laravel4-etag-conditional-get.

Option 3. is a direct result of that article because I started looking into the
$response object as a whole, and saw, much to my surprise, that we have a member
`original` in the Response instance that we can get to by calling
`$response->getOriginalContent()`. For GET requests, then, where the response
has an Eloquent Collection, we can calculate ETags for each Model in that
collection, just before the response is sent to the API consumer.
We can put the Model's ID in combination with the ETag in the response headers,
and we can list multiple combinations like that by using the semicolon to
separate entities. All we have to do now, when we commit an index response to
Vuex, is parse the ETag header, and store the ETag with the individual object in
Vuex. Thus we can use that ETag value next time the SPA uses the DELETE, GET or
PUT method on the given model. We can then also implement a response interceptor
that verifies for 304 responses or for 412 in case of mid-air collisions.
