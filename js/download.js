OCA.Files.fileActions.registerAction({
    name: 'force-download',
    displayName: 'Force download',
    mime: 'force-download',
    permissions: OC.PERMISSION_READ,
    actionHandler: function(filename, context) {
        var dir = context.dir || context.fileList.getCurrentDirectory();
        var isDir = context.$file.attr('data-type') === 'dir';
        var url = context.fileList.getDownloadUrl(filename, dir, isDir);

        const link = document.createElement('a')
        link.href = url
        link.download = true
        link.click()
    },
})

OCA.Files.fileActions.setDefault('force-download', 'force-download')