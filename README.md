# Mutual Aid NYC WordPress Theme
A WordPress theme developed for Mutual Aid NYC, using the parent theme TwentyTwenty.

## Development
This project requires:
- Node.js (latest LTS version)
- Yarn
- Docker
- Basic knowledge of WordPress
- NVM (optional)
- Composer (optional)

To install this project:
1. Clone this repo to a directory and `cd` into it.
2. Install dependencies with `yarn`.
3. Initialize the site with `yarn wp-env start`.
4. Access the site at `localhost:8888`. [The default user is `admin`, password `password`](https://developer.wordpress.org/block-editor/packages/packages-env/#starting-the-environment).
5. Install the following plugins: AirPress, Jetpack, Gutenberg, TranslatePress (optional).
6. Build the blocks via the following command: `yarn 
7. Activate the Mutual Aid NYC theme in the admin.
8. Optional: Export the site from production or stage, and import via the WordPress importer tool.
9. Optional: Run `composer install` from repo root to install PHP linting tools. Linting can be run via `composer run-script php:lint`.

## Release Process
The release process is mostly automated, being driven by GitHub Actions for the most part.

Required:
- GitHub maintainer access for this repo
- Production WordPress admin level access.

1. Ensure current `main` branch has tests currently passing.
2. Create a [new release](https://github.com/MutualAidNYC/wp-theme/releases/new) in GitHub with a new tag for the released version.
3. Set the description to link to the [currently open milestone](https://github.com/MutualAidNYC/wp-theme/milestones).
4. A new theme package will be built, and attached to the new release as `mutualaidnyc.zip`. Progress can be monitored [here](https://github.com/MutualAidNYC/wp-theme/actions?query=workflow%3A%22Package+theme+for+release%22).
5. Download the package zip file and upload it via the production Wordpress admin.
6. Create a [new Milestone](https://github.com/MutualAidNYC/wp-theme/milestones/new) in GitHub with the **next** release version.
7. Notify people in Slack with the appropriate amount of emoji. 🎉
