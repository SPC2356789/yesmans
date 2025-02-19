function it({
                state: u,
                statePath: a,
                selector: p,
                plugins: m,
                external_plugins: f,
                toolbar: h,
                language: g = "zh_TW",
                language_url: b = null,
                directionality: _ = "ltr",
                height: w = null,
                max_height: y = 0,
                min_height: C = 100,
                width: k = null,
                max_width: v = 0,
                min_width: E = 400,
                resize: x = !1,
                skin: A = "oxide",
                content_css: T = "default",
                toolbar_sticky: F = !0,
                toolbar_sticky_offset: q = 64,
                toolbar_mode: G = "sliding",
                toolbar_location: K = "auto",
                inline: L = !1,
                toolbar_persist: M = !1,
                menubar: $ = !1,
                font_size_formats: P = "",
                fontfamily: r = "",
                relative_urls: z = !0,
                image_list: D = null,
                image_advtab: O = !1,
                image_description: V = !1,
                image_class_list: W = null,
                images_upload_url: B = null,
                images_upload_base_path: c = null,
                remove_script_host: H = !0,
                convert_urls: I = !0,
                custom_configs: J = {},
                setup: d = null,
                disabled: N = !1,
                locale: Q = "en",
                license_key: S = "gpl",
                placeholder: lt = null
            }) {
    let s = window.filamentTinyEditors || {};
    return {
        id: null,
        state: u,
        statePath: a,
        selector: p,
        language: g,
        language_url: b,
        directionality: _,
        height: w,
        max_height: y,
        min_height: C,
        width: k,
        max_width: v,
        min_width: E,
        resize: x,
        skin: A,
        content_css: T,
        plugins: m,
        external_plugins: f,
        toolbar: h,
        toolbar_sticky: F,
        menubar: $,
        relative_urls: z,
        remove_script_host: H,
        convert_urls: I,
        font_size_formats: P,
        fontfamily: r,
        setup: d,
        image_list: D,
        image_advtab: O,
        image_description: V,
        image_class_list: W,
        images_upload_url: B,
        images_upload_base_path: c,
        license_key: S,
        custom_configs: J,
        updatedAt: Date.now(),
        disabled: N,
        locale: Q,
        init() {
            this.delete(), this.initEditor(u.initialValue), window.filamentTinyEditors = s, this.$watch("state", (e, o) => {
                e === "<p></p>" && e !== this.editor()?.getContent() && (this.delete(), this.initEditor(e)), this.editor()?.container && e !== this.editor()?.getContent() && (this.updateEditorContent(e || ""), this.putCursorToEnd())
            })
        },
        editor() {
            return tinymce.get(s[this.statePath])
        },
        initEditor(e) {
            let o = this, U = this.$wire, R = {
                selector: p,
                language: g,
                language_url: b,
                directionality: _,
                statusbar: !1,
                promotion: !1,
                height: w,
                max_height: y,
                min_height: C,
                width: k,
                max_width: v,
                min_width: E,
                resize: x,
                skin: A,
                content_css: T,
                plugins: m,
                external_plugins: f,
                toolbar: h,
                toolbar_sticky: F,
                toolbar_sticky_offset: q,
                toolbar_mode: G,
                toolbar_location: K,
                inline: L,
                toolbar_persist: M,
                menubar: $,
                menu: {
                    file: {
                        title: "File",
                        items: "newdocument restoredraft | preview | export print | deleteallconversations"
                    },
                    edit: {title: "Edit", items: "undo redo | cut copy paste pastetext | selectall | searchreplace"},
                    view: {
                        title: "View",
                        items: "code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments"
                    },
                    insert: {
                        title: "Insert",
                        items: "image link media addcomment pageembed codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime"
                    },
                    format: {
                        title: "Format",
                        items: "bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat"
                    },
                    tools: {title: "Tools", items: "spellchecker spellcheckerlanguage | a11ycheck code wordcount"},
                    table: {
                        title: "Table",
                        items: "inserttable | cell row column | advtablesort | tableprops deletetable"
                    },
                    help: {title: "Help", items: "help"}
                },
                font_size_formats: P,
                fontfamily: r,
                font_family_formats: r,
                relative_urls: z,
                remove_script_host: H,
                convert_urls: I,
                image_list: D,
                image_advtab: O,
                image_description: V,
                image_class_list: W,
                images_upload_url: B,
                images_upload_base_path: c,
                license_key: S, ...J,
                setup: function (t) {
                    window.tinySettingsCopy || (window.tinySettingsCopy = []), t.settings && !window.tinySettingsCopy.some(i => i.id === t.settings.id) && window.tinySettingsCopy.push(t.settings), t.on("blur", function (i) {
                        o.updatedAt = Date.now(), o.state = t.getContent()
                    }), t.on("change", function (i) {
                        o.updatedAt = Date.now(), o.state = t.getContent()
                    }), t.on("init", function (i) {
                        s[o.statePath] = t.id, e != null && t.setContent(e)
                    }), t.on("OpenWindow", function (i) {
                        let l = i.target.container.closest(".fi-modal");
                        l && l.setAttribute("x-trap.noscroll", "false")
                    }), t.on("CloseWindow", function (i) {
                        let l = i.target.container.closest(".fi-modal");
                        l && l.setAttribute("x-trap.noscroll", "isOpen")
                    }), typeof d == "function" && d(t)
                },
                images_upload_handler: (t, i) => new Promise((l, X) => {
                    if (!t.blob()) return;
                    let Y = (n, j) => n ? n.replace(/\/$/, "") + "/" + j.replace(/^\//, "") : j, Z = () => {
                        U.getFormComponentFileAttachmentUrl(a).then(n => {
                            if (!n) {
                                X("Image upload failed");
                                return
                            }
                            l(Y(c, n))
                        })
                    }, tt = () => {
                    }, et = n => {
                        i(n.detail.progress)
                    };
                    U.upload(`componentFileAttachments.${a}`, t.blob(), Z, tt, et)
                }),
                automatic_uploads: !0
            };
            tinymce.init(R)
        },
        updateEditorContent(e) {
            this.editor().setContent(e)
        },
        putCursorToEnd() {
            this.editor().selection.select(this.editor().getBody(), !0), this.editor().selection.collapse(!1)
        },
        delete() {
            s[this.statePath] && (this.editor().destroy(), delete s[this.statePath])
        }
    }
}

export {it as default};
