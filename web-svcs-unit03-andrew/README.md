Exercise 2: PhlyRestfully
=========================

In this exercise, we'll use
[PhlyRestfully](https://github.com/weierophinney/PhlyRestfully) to implement a
RESTful JSON API.

Since PhlyRestfully builds on top of ZF2's REST capabilities, the approach is
somewhat different and simplified. Your tasks are:

- Fill in the `Status` class (`module/Status/src/Status/Status.php`):
  - add properties for "id" and "text".
  - add getters and setters for those properties.
- Fill in the `StatusListener` class (`module/Status/src/Status/StatusListener.php`):
  - Use the composed TableGateway to fill in the `onPatch()`, `onDelete()`,
    `onFetch()`, and `onFetchAll()` methods, according to the instructions in
    their docblocks.
- Fill in the `module/Status/config/module.config.php` class:
  - Add the `metadata_map` information for the `Status\Status` class.
  - Fill in the information for the `Status\StatusController` resource.

Try it out
----------

Create a vhost, or, more easily, fire up PHP 5.6's built-in web server in this
directory. Use cURL, httpie, or a browser-based REST console to access the API,
which will be available at the paths denoted by `/api/status[/:id]`.

Try each of the following:

- Retrieving a list of statuses; note the pagination, and try different pages.
- Retrieving a single status
- Creating a status
- Deleting a single status
- Updating a single status
- Attempting each of the above operations on a non-existent, valid identifier
