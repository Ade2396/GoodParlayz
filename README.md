GoodParlayz — Sports Betting Dashboard

GoodParlayz is a simple web application that lets users create an account, log in, manage profile information, save favorite teams, and create/delete parlay slips. 

Features Implemented
1. User Authentication

Signup page (signup.php)

Login page (login.php)

Session-based authentication


2. Dashboard / Homepage

Displays user info after login

Shows custom title and team logos

Navigation links to profile, favorites, and parlay pages

3. Update Feature

Edit Profile
Users can update display name, email, or password from profile.php.

4. Delete Feature

Delete Parlay Slip
Users can delete individual parlay entries.

Delete Account 

5. Additional Feature

Favorite Teams System
Users can add or remove favorite sports teams. Stored in the favorite_teams table.

6. Parlay Slip Creation

Users can add a new parlay with matchup and stake (odds removed by design).

Preset matchups pull from the database `matchups`/`teams` tables when available (fallback list: Bulls vs Pistons, Eagles vs 49ers, Giants vs Cowboys, Heat vs Celtics, Lakers vs Warriors, Ravens vs Steelers).


Features NOT Implemented

No live sports API integration (scores, odds, real-time data)

No full user settings page

No parlay slip editing (only creating and deleting)

Known Bugs / Limitations

UI layout is basic and can shift on smaller screens.

Password reset feature not included.

Delete account (if included) is final with no warning.

No password strength checking.


Credits

Team logos belong to their respective sports organizations.
