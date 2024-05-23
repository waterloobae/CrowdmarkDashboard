# CrowdmarkDashboard

## Release Notes : version 0.1
API call for responses is made for each booklet, that created around 2,500 API calls. As a result of that, it take around 30 minutes to create one Assessment. /api/responses/*response_id* is used for this.

Instead of using /api/questions/question_id/responses. Multi Curls for /api/booklets/booklet_id/responses are used. It reduces response time from 30 minutes to 2 minutes.
