import * as FilePond from 'filepond'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginImageCrop from 'filepond-plugin-image-crop'
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import FilePondPluginImageResize from 'filepond-plugin-image-resize'
import FilePondPluginImageTransform from 'filepond-plugin-image-transform'
import FilePondPluginMediaPreview from 'filepond-plugin-media-preview'

FilePond.registerPlugin(FilePondPluginFileValidateSize)
FilePond.registerPlugin(FilePondPluginFileValidateType)
FilePond.registerPlugin(FilePondPluginImageCrop)
FilePond.registerPlugin(FilePondPluginImageExifOrientation)
FilePond.registerPlugin(FilePondPluginImagePreview)
FilePond.registerPlugin(FilePondPluginImageResize)
FilePond.registerPlugin(FilePondPluginImageTransform)
FilePond.registerPlugin(FilePondPluginMediaPreview)

window.FilePond = FilePond

export default function fileUploadFormComponent({
    acceptedFileTypes,
    deleteUploadedFileUsing,
    getUploadedFilesUsing,
    imageCropAspectRatio,
    imagePreviewHeight,
    imageResizeMode,
    imageResizeTargetHeight,
    imageResizeTargetWidth,
    imageResizeUpscale,
    isAvatar,
    isDownloadable,
    isOpenable,
    isPreviewable,
    isReorderable,
    loadingIndicatorPosition,
    locale,
    panelAspectRatio,
    panelLayout,
    placeholder,
    maxSize,
    minSize,
    removeUploadedFileButtonPosition,
    removeUploadedFileUsing,
    reorderUploadedFilesUsing,
    shouldAppendFiles,
    shouldOrientImageFromExif,
    shouldTransformImage,
    state,
    uploadButtonPosition,
    uploadProgressIndicatorPosition,
    uploadUsing,
}) {
    return {
        fileKeyIndex: {},

        pond: null,

        shouldUpdateState: true,

        state,

        lastState: null,

        uploadedFileIndex: {},

        init: async function () {
            FilePond.setOptions(locales[locale] ?? locales['en'])

            this.pond = FilePond.create(this.$refs.input, {
                acceptedFileTypes,
                allowImageExifOrientation: shouldOrientImageFromExif,
                allowPaste: false,
                allowReorder: isReorderable,
                allowImagePreview: isPreviewable,
                allowVideoPreview: isPreviewable,
                allowAudioPreview: isPreviewable,
                allowImageTransform: shouldTransformImage,
                credits: false,
                files: await this.getFiles(),
                imageCropAspectRatio,
                imagePreviewHeight,
                imageResizeTargetHeight,
                imageResizeTargetWidth,
                imageResizeMode,
                imageResizeUpscale,
                itemInsertLocation: shouldAppendFiles ? 'after' : 'before',
                ...(placeholder && { labelIdle: placeholder }),
                maxFileSize: maxSize,
                minFileSize: minSize,
                styleButtonProcessItemPosition: uploadButtonPosition,
                styleButtonRemoveItemPosition: removeUploadedFileButtonPosition,
                styleLoadIndicatorPosition: loadingIndicatorPosition,
                stylePanelAspectRatio: panelAspectRatio,
                stylePanelLayout: panelLayout,
                styleProgressIndicatorPosition: uploadProgressIndicatorPosition,
                server: {
                    load: async (source, load) => {
                        let response = await fetch(source, {
                            cache: 'no-store',
                        })
                        let blob = await response.blob()

                        load(blob)
                    },
                    process: (
                        fieldName,
                        file,
                        metadata,
                        load,
                        error,
                        progress,
                    ) => {
                        this.shouldUpdateState = false

                        let fileKey = (
                            [1e7] +
                            -1e3 +
                            -4e3 +
                            -8e3 +
                            -1e11
                        ).replace(/[018]/g, (c) =>
                            (
                                c ^
                                (crypto.getRandomValues(new Uint8Array(1))[0] &
                                    (15 >> (c / 4)))
                            ).toString(16),
                        )

                        uploadUsing(
                            fileKey,
                            file,
                            (fileKey) => {
                                this.shouldUpdateState = true

                                load(fileKey)
                            },
                            error,
                            progress,
                        )
                    },
                    remove: async (source, load) => {
                        let fileKey = this.uploadedFileIndex[source] ?? null

                        if (!fileKey) {
                            return
                        }

                        await deleteUploadedFileUsing(fileKey)

                        load()
                    },
                    revert: async (uniqueFileId, load) => {
                        await removeUploadedFileUsing(uniqueFileId)

                        load()
                    },
                },
            })

            this.$watch('state', async () => {
                if (!this.shouldUpdateState) {
                    return
                }

                // We don't want to overwrite the files that are already in the input, if they haven't been saved yet.
                if (
                    this.state !== null &&
                    Object.values(this.state).filter((file) =>
                        file.startsWith('livewire-file:'),
                    ).length
                ) {
                    this.lastState = null

                    return
                }

                // Don't do anything if the state hasn't changed
                if (JSON.stringify(this.state) === this.lastState) {
                    return
                }

                this.lastState = JSON.stringify(this.state)

                this.pond.files = await this.getFiles()
            })

            this.pond.on('reorderfiles', async (files) => {
                const orderedFileKeys = files
                    .map((file) =>
                        file.source instanceof File
                            ? file.serverId
                            : this.uploadedFileIndex[file.source] ?? null,
                    ) // file.serverId is null for a file that is not yet uploaded
                    .filter((fileKey) => fileKey)

                await reorderUploadedFilesUsing(
                    shouldAppendFiles
                        ? orderedFileKeys
                        : orderedFileKeys.reverse(),
                )
            })

            this.pond.on('initfile', async (fileItem) => {
                if (!isDownloadable) {
                    return
                }

                if (isAvatar) {
                    return
                }

                this.insertDownloadLink(fileItem)
            })

            this.pond.on('initfile', async (fileItem) => {
                if (!isOpenable) {
                    return
                }

                if (isAvatar) {
                    return
                }

                this.insertOpenLink(fileItem)
            })

            this.pond.on('processfilestart', async () => {
                this.dispatchFormEvent('file-upload-started')
            })

            this.pond.on('processfileprogress', async () => {
                this.dispatchFormEvent('file-upload-started')
            })

            this.pond.on('processfile', async () => {
                this.dispatchFormEvent('file-upload-finished')
            })

            this.pond.on('processfiles', async () => {
                this.dispatchFormEvent('file-upload-finished')
            })

            this.pond.on('processfileabort', async () => {
                this.dispatchFormEvent('file-upload-finished')
            })

            this.pond.on('processfilerevert', async () => {
                this.dispatchFormEvent('file-upload-finished')
            })
        },

        dispatchFormEvent: function (name) {
            this.$el.closest('form')?.dispatchEvent(
                new CustomEvent(name, {
                    composed: true,
                    cancelable: true,
                }),
            )
        },

        getUploadedFiles: async function () {
            const uploadedFiles = await getUploadedFilesUsing()

            this.fileKeyIndex = uploadedFiles ?? {}

            this.uploadedFileIndex = Object.entries(this.fileKeyIndex)
                .filter(([key, value]) => value?.url)
                .reduce((obj, [key, value]) => {
                    obj[value.url] = key

                    return obj
                }, {})
        },

        getFiles: async function () {
            await this.getUploadedFiles()

            let files = []

            for (const uploadedFile of Object.values(this.fileKeyIndex)) {
                if (!uploadedFile) {
                    continue
                }

                files.push({
                    source: uploadedFile.url,
                    options: {
                        type: 'local',
                        ...(/^image/.test(uploadedFile.type)
                            ? {}
                            : {
                                  file: {
                                      name: uploadedFile.name,
                                      size: uploadedFile.size,
                                      type: uploadedFile.type,
                                  },
                              }),
                    },
                })
            }

            return shouldAppendFiles ? files : files.reverse()
        },

        insertDownloadLink: function (file) {
            if (file.origin !== FilePond.FileOrigin.LOCAL) {
                return
            }

            const anchor = this.getDownloadLink(file)

            if (!anchor) {
                return
            }

            document
                .getElementById(`filepond--item-${file.id}`)
                .querySelector('.filepond--file-info-main')
                .prepend(anchor)
        },

        insertOpenLink: function (file) {
            if (file.origin !== FilePond.FileOrigin.LOCAL) {
                return
            }

            const anchor = this.getOpenLink(file)

            if (!anchor) {
                return
            }

            document
                .getElementById(`filepond--item-${file.id}`)
                .querySelector('.filepond--file-info-main')
                .prepend(anchor)
        },

        getDownloadLink: function (file) {
            let fileSource = file.source

            if (!fileSource) {
                return
            }

            const anchor = document.createElement('a')
            anchor.className = 'filepond--download-icon'
            anchor.href = fileSource
            anchor.download = file.file.name

            return anchor
        },

        getOpenLink: function (file) {
            let fileSource = file.source

            if (!fileSource) {
                return
            }

            const anchor = document.createElement('a')
            anchor.className = 'filepond--open-icon'
            anchor.href = fileSource
            anchor.target = '_blank'

            return anchor
        },
    }
}

import ar from 'filepond/locale/ar-ar'
import cs from 'filepond/locale/cs-cz'
import da from 'filepond/locale/da-dk'
import de from 'filepond/locale/de-de'
import en from 'filepond/locale/en-en'
import es from 'filepond/locale/es-es'
import fa from 'filepond/locale/fa_ir'
import fi from 'filepond/locale/fi-fi'
import fr from 'filepond/locale/fr-fr'
import hu from 'filepond/locale/hu-hu'
import id from 'filepond/locale/id-id'
import it from 'filepond/locale/it-it'
import nl from 'filepond/locale/nl-nl'
import pl from 'filepond/locale/pl-pl'
import pt_BR from 'filepond/locale/pt-br'
import pt_PT from 'filepond/locale/pt-br'
import ro from 'filepond/locale/ro-ro'
import ru from 'filepond/locale/ru-ru'
import sv from 'filepond/locale/sv_se'
import tr from 'filepond/locale/tr-tr'
import uk from 'filepond/locale/uk-ua'
import vi from 'filepond/locale/vi-vi'
import zh_CN from 'filepond/locale/zh-cn'
import zh_TW from 'filepond/locale/zh-tw'

const locales = {
    ar,
    cs,
    da,
    de,
    en,
    es,
    fa,
    fi,
    fr,
    hu,
    id,
    it,
    nl,
    pl,
    pt_BR,
    pt_PT,
    ro,
    ru,
    sv,
    tr,
    uk,
    vi,
    zh_CN,
    zh_TW,
}
