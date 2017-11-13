# Changelog

### Assignment 2 - November 12, 2017

#### Models

- Added model to retrieve information from the Wacky Servers.

-  All models are storing data in .csv file formats only.

- Changed Fleet collection to load data from CSV files and the Wacky Server instead of hard-coded values.  Added Validation for Fleet budget.

- Added single Airplane Entity Model.  Validates ID and airplane code.

- Changed FlightsModel to ScheduleModel.  Validates network visits and base returns.

- Added entity model for Flight Segment.   Validates times and airports.

- Added phpunit testing for all models (Fleet, Plane, Schedule, Flight).

#### Views

- Added drop-down to select from either Admin or Guest roles.

- Separated the Flights page into several smaller more manageable views.

- Added a view for viewing and editing the information for a Flight.

- Added a view for viewing and editing the information for a Plane.

- Modified view for single Flight to allow for editing of fields by the Admin role

- Modified view for single Plane to allow for editing of fields by the Admin role.

- Added view for the booking of Flights.

- Added button on homepage to book Flights.

#### Controllers

- Changed the Fleet controller to allow for the editing of single Planes by the Admin role.

- Changed the Flight controller to allow for the editing of single Planes by the Admin role.

- Created controller for booking flights.
