# UP NEXT
- Make each User editable
- Prices
- MULTI-TENANCY?
- Continue Top Menu Toggles
- Datepicker Frontend TimeTracking
- add Pagination
- Sorting in Frontend
- Protecting Message Endpoints?

# Bugs
- When adding a User, it will get pushed to the Userlist, but missing the roles
  - Possible Cause: The Response contains the Roles with "ROLE_" prefix
  - Possible Solution: Transform Values with Api Platform Annotations?
  - Same for loading the User in Edit Mode

# TO SCALE
- Add Pagination to Collection endpoints (currently 10000 max.)

# DONE
- ~~Add Confirmation Modal to Delete~~
- ~~Disallow Deletion of Self in Users~~
- ~~Toggle Bottom Bar when User selected (for batch actions / blog-list.html)~~
- ~~Add "Settings" to configure from Frontend~~
- ~~Start Stundenzettel FE~~
- ~~Enrich Token with Full Name~~
- ~~Continue left Menu~~ (good enough)
- ~~- Add new TimeTracking from Frontend~~
- ~~Refactor all Fronted API Services to use JSON-LD~~ FK THAT LD SHT
