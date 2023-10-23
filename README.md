# LibOps

See [LibOps Development Documentation](https://libops.github.io/documentation/development/) for full information.

## Overview

> [!NOTE]  
> | Environment | URL |
> | --- | --- |
> | development | https://drupal-development-tugvpitiuq-uc.a.run.app |
> | production  | https://demo.libops.io |

The basics are:

1. Pushing commits to the `development` branch automatically deploys the git branch to your development environment
2. Publishing [a release in GitHub](./../../releases/new) deploys the release tag to your production environment
3. Pushing commits to a new branch automatically creates a new environment and deploys the branch to your new environment.

## License

In following with GPLv2, this repo was originally forked from [islandora-devops/islandora-starter-site](https://github.com/islandora-devops/islandora-starter-site).
The most notable changes after forking have been:

- Replaced the fedora flysystem configuration for Drupal file entities and Islandora derivative actions to instead use [drupal/flysystem_gcs](https://www.drupal.org/project/flysystem_gcs)
- Configured file uploads on media entities to use [drupal/flysystem_gcs_cors](https://www.drupal.org/project/flysystem_gcs_cors) to directly upload files to Google Cloud Storage from the client's web browser
- Configured the Google Cloud Storage URIs to index into Fedora as external content
- Added GitHub Actions to this repo that are required for LibOps functionality
- Added a Drupal module required for running on the LibOps platform
- Removed bibcite/citation_selection modules in favor of [islandora_csl](https://www.drupal.org/project/islandora_csl)
- Added the patch from [islandora/islandora#968](https://github.com/Islandora/islandora/pull/968) to remove features dependency ahead of the PR being merged into the main project
- Upgraded the PDF.js library and added a patch to allow valid LibOps buckets to display in PDF viewer.
- Added [bootstrap_barrio](https://www.drupal.org/project/bootstrap_barrio) and created a subtheme of bootstrap_barrio theme called isle
- Replaced Openseadragon with [Islandora Mirador](https://github.com/islandora/islandora_mirador)
- Configured hOCR text creation per Islandora Mirador's instructions
- Updated `/search` page, removing [islandora/advanced_search](https://github.com/islandora/advanced_search)
- Added [drupal/field_group_table](https://www.drupal.org/project/field_group_table) and configured item level metadata to display using the formatter
- Added [drupal/entity_browser](https://www.drupal.org/project/entity_browser) and [drupal/inline_entity_form](https://www.drupal.org/project/inline_entity_form) and configured field_member_of to use it on the repository node edit form
- Added/configured [drupal/metatag_google_scholar](https://www.drupal.org/project/metatag_google_scholar)
- Added [drupal/views_bootstrap](https://www.drupal.org/project/views_bootstrap) and applied bootstrap style to solr search

