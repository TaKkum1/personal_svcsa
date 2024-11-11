# Modify Database

## Update Database Schema

### Database Schema Update To-Do List

- [ ] Backup the existing database to ensure data safety.
- [ ] Open an issue in Github about what the change is for and what schema change proposed.
- [ ] Communicate to the group and get `ok`
- [ ] Draft a Pull Request of changes in `db_backup` folder.    **Check below about the required content in the PR**. 
- [ ] Update the **beta** mySQL to reflect the new schema changes.
- [ ] Test the schema update in the staging(beta) environment.
- [ ] Deploy the updated schema to the production database.
- [ ] Validate the schema update in the production environment.
- [ ] Communicate the successful schema update to the team and stakeholders.

### Github Issue template
```
### Description
### What table(s) affected?
### Is the schema change backward compatible?
### Require view changes?

```

### Pull Request Checklist
- [ ] Create a new folder under `db_backup`, with a version bump
- [ ] A .md file about the schema change. Including the link to the issue and the SQL statement to modify the database (or migration file)
- [ ] A .md file to include **ALL** view creation schema.
- [ ] A copy dump of database schema and data. (the full DB before the schema change)

Example:
```
-- db_backup
    |-- 2.0.0
    |-- 2.0.1
    ...
    |-- 2.0.x
        |--{{schema-only}}.sql
        |--{{schema-and-data}}.sql
        |--database-view.md
        |--changelog.md

```



## Update Database View

### Database View Update To-Do List

- [ ] Communicate to the group and get `ok`
- [ ] Draft a Pull Request of changes in the **latest** version of the database in the `db_backup` folder.   ** Check below about the required content in the PR **  
- [ ] Update the **beta** mySQL to reflect the new schema changes.
- [ ] Test the schema update in the staging(beta) environment.
- [ ] Deploy the updated schema to the production database.
- [ ] Validate the schema update in the production environment.
- [ ] Communicate the successful schema update to the team and stakeholders.


### Pull Request Checklist
- [ ] Update the view creation .md file to include the modified view creation statement.
- [ ] A descrpition in the pull request to discribe the changes. Including, not limited to, affected tables/views/etc






