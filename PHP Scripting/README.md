# Movies (Local) [1.0.0]
Search for your favorite movies right in Moodle! Install through site-adminstration plugin settings.
Integrated with the [OMDb API](https://www.omdbapi.com/) to serve endless titles on demand.

> This plugin requires an API key which can be obtained from OMDb [here](https://www.omdbapi.com/apikey.aspx). The key can be set within the plugin settings.

## Assumptions
In this instance, my assumptions are that the all prerequisites required by Moodle 3.11+ are setup on the production server.

Additionally, at minimum, the following assumptions are made:
- **Minimum Moodle version:** 3.11+
- **Users will be using:** [Chrome, Firefox, Safari or Edge]
- **PHP Extensions:** Prerequisites required by Moodle
- **Production Server Running:** [Apache, Nginx or any other supported web servers]
- **Theme Styling:** Not conflicting with standard Bootstrap/Moodle utility classes in an unresponsive manner
- **Domain:** HTTPS (Impacts HTTP requests on certain APIs)
- **OMDb:** 
    - API servers are available
    - No major change has been made to the OMDb API since [RELEASE] 1.0.0 of local_movies
    - API-Key provided is valid

## Provisions

### Production
- Validate Moodle version
- Validate PHP version (Must be supported on 3.11+)
- Install and test local_movie. These will be manual browser tests
- Revert back to pre-production in the event of issues, rerunning unit tests (or writing new ones where necessary)

### Preproduction
- Setup appropriate Moodle version
- If not already installed, setup Composer and install PHPUnit
- Initialize git repository for plugin
- Enable developer debug mode with `debugdisplay` enabled