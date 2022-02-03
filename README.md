# Force Download

Force download will simple force to download all associated apps.

## How to usage

You will need create two files following the example:

`config/mimetypemapping.json`
```json
{
    "mp4": ["force-download"]
}
```

`config/mimetypealiases.json`
```json
{
    "force-download": "video/mp4"
}
```

After, you will need run the follow `occ` commands:

```bash
# Update the core/js/mimetypelist.js file:
occ maintenance:mimetype:update-js
# redefine all fileinfo on database to have the new mimetype
occ files:scan --all
```

Now, all files `.mp4` will be downloaded and don't will open on app [Viewer](https://github.com/nextcloud/viewer/)