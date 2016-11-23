/*
 * Slim v1.0.2 - Image Cropping Made Easy
 * Copyright (c) 2016 Rik Schennink - http://slim.pqina.nl
 */
window.Slim = function() {

    function _classCallCheck(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    function getOffsetByEvent(t) {
        return {
            x: "undefined" == typeof t.offsetX ? t.layerX : t.offsetX,
            y: "undefined" == typeof t.offsetY ? t.layerY : t.offsetY
        }
    }

    function mergeOptions(t, e) {
        var i, n = {},
            a = e || {};
        for (i in t) t.hasOwnProperty(i) && (n[i] = "undefined" == typeof a[i] ? t[i] : a[i]);
        return n
    }

    function create(t, e) {
        var i = document.createElement(t);
        return e && (i.className = e), i
    }

    function addEvents(t, e, i) {
        e.forEach(function(e) {
            t.addEventListener(e, i, !1)
        })
    }

    function removeEvents(t, e, i) {
        e.forEach(function(e) {
            t.removeEventListener(e, i, !1)
        })
    }

    function getEventOffset(t) {
        var e = t.changedTouches ? t.changedTouches[0] : t;
        if (e) return {
            x: e.pageX,
            y: e.pageY
        }
    }

    function getEventOffsetLocal(t, e) {
        var i = getEventOffset(t),
            n = e.getBoundingClientRect(),
            a = window.pageYOffset || document.documentElement.scrollTop,
            o = window.pageXOffset || document.documentElement.scrollLeft;
        return {
            x: i.x - n.left - o,
            y: i.y - n.top - a
        }
    }

    function capitalizeFirstLetter(t) {
        return t.charAt(0).toUpperCase() + t.slice(1)
    }

    function last(t) {
        return t[t.length - 1]
    }

    function limit(t, e, i) {
        return Math.max(e, Math.min(i, t))
    }

    function cloneRect(t) {
        return {
            x: t.x,
            y: t.y,
            width: t.width,
            height: t.height
        }
    }

    function inArray(t, e) {
        return -1 !== e.indexOf(t)
    }

    function getExtensionFromFileName(t) {
        return t.split(".").pop().toLowerCase()
    }

    function urlToImage(t) {
        var e = new Image;
        return e.src = t, e
    }

    function imageToData(t) {
        var e, i, n;
        return e = document.createElement("canvas"), e.width = t.naturalWidth, e.height = t.naturalHeight, i = e.getContext("2d"), i.drawImage(t, 0, 0), n = e.toDataURL()
    }

    function send(t, e, i, n, a) {
        var o = new XMLHttpRequest;
        i && o.upload.addEventListener("progress", function(t) {
            i(t.loaded, t.total)
        }), o.open("POST", t, !0), o.onreadystatechange = function() {
            if (4 === o.readyState && 200 === o.status) {
                var t = o.responseText;
                if (!t.length) return void n();
                if (-1 !== t.indexOf("Content-Length")) return void a("file-too-big");
                var e = null;
                try {
                    e = JSON.parse(o.responseText)
                } catch (i) {
                    return void a("invalid-json")
                }
                n(e)
            } else 4 === o.readyState && a("fail")
        }, o.send(e)
    }

    function resetTransforms(t) {
        t.style.transform = ""
    }

    function revealElement(t, e) {
        snabbt(t, {
            fromOpacity: 0,
            opacity: 1,
            duration: e,
            complete: function() {
                resetTransforms(this)
            }
        })
    }

    function bytesToMegaBytes(t) {
        return t / 1e6
    }

    function megaBytesToBytes(t) {
        return 1e6 * t
    }

    function getCommonMimeTypes() {
        var t = [],
            e = void 0,
            i = void 0;
        for (e in mimetypes) i = mimetypes[e], -1 == t.indexOf(i) && t.push(i);
        return t
    }

    function getExtensionByMimeType(t) {
        var e = void 0;
        for (e in mimetypes)
            if (mimetypes[e] === t) return e;
        return t
    }

    function getFileMimeType(t) {
        return mimetypes[t] || "unknown"
    }

    function getFileMetaData(t) {
        return "string" == typeof t ? {
            name: t.split("/").pop(),
            type: getFileMimeType(t.split(".").pop()),
            size: null
        } : {
            name: t.name,
            type: t.type,
            size: t.size
        }
    }

    function getImage(t) {
        return loadImage(t)
    }

    function getImageAsCanvas(t, e) {
        loadImage.parseMetaData(t, function(i) {
            var n = {
                canvas: !0
            };
            i.exif && (n.orientation = i.exif.get("Orientation")), loadImage(t, function(t) {
                return "error" === t.type ? void e() : void e(t, i)
            }, n)
        })
    }

    function getAutoCropRect(t, e, i) {
        var n, a, o, r, s = e / t;
        return i > s ? (r = e, o = r / i, n = .5 * (t - o), a = 0) : (o = t, r = o * i, n = 0, a = .5 * (e - r)), {
            x: n,
            y: a,
            height: r,
            width: o
        }
    }

    function cropImage(t, e, i) {
        "toDataURL" in t && (t = t.toDataURL()), loadImage(t, function(t) {
            e(t)
        }, {
            canvas: !0,
            left: i.x,
            top: i.y,
            sourceWidth: i.width,
            sourceHeight: i.height
        })
    }

    function transformCanvas(t, e, i) {
        void 0 === e && (e = {});
        var n = create("canvas"),
            a = e.crop,
            o = e.size;
        if (a) {
            n.width = a.width, n.height = a.height;
            var r = n.getContext("2d");
            r.drawImage(t, a.x, a.y, a.width, a.height, 0, 0, a.width, a.height)
        }
        o && scaleCanvas(n, o.width / n.width), i(n)
    }

    function cropCanvas(t, e, i) {
        var n = create("canvas");
        n.width = i.width, n.height = i.height;
        var a = n.getContext("2d");
        a.drawImage(t, i.x, i.y, i.width, i.height, 0, 0, i.width, i.height), e(n)
    }

    function scaleCanvas(t, e) {
        if (!(e >= 1)) {
            for (var i, n = cloneCanvas(t), a = t.width, o = t.height, r = Math.ceil(t.width * e), s = Math.ceil(t.height * e); a > r && (a *= .5, o *= .5, !(r > a));) n = create("canvas"), n.width = a, n.height = o, i = n.getContext("2d"), i.drawImage(t, 0, 0, a, o);
            t.width = r, t.height = s, i = t.getContext("2d"), i.drawImage(n, 0, 0, r, s)
        }
    }

    function cloneCanvas(t) {
        return cloneCanvasScaled(t, 1)
    }

    function cloneCanvasScaled(t, e) {
        var i = document.createElement("canvas");
        i.setAttribute("data-file", t.getAttribute("data-file"));
        var n = i.getContext("2d");
        return i.width = t.width, i.height = t.height, n.drawImage(t, 0, 0), e > 0 && 1 != e && scaleCanvas(i, e), i
    }

    function canvasHasDimensions(t) {
        return t.width && t.height
    }

    function copyCanvas(t, e) {
        var i = e.getContext("2d");
        canvasHasDimensions(e) ? i.drawImage(t, 0, 0, e.width, e.height) : (e.width = t.width, e.height = t.height, i.drawImage(t, 0, 0))
    }

    function clearCanvas(t) {
        var e = t.getContext("2d");
        e.clearRect(0, 0, t.width, t.height)
    }

    function blurCanvas(t) {
        stackBlur(t, 0, 0, t.width, t.height, 3)
    }

    function rotateCanvas(t) {
        var e = document.createElement("canvas"),
            i = e.getContext("2d"),
            n = .5 * t.width,
            a = .5 * t.height;
        return e.width = t.height, e.height = t.width, clearCanvas(e), i.translate(a, n), i.rotate(90 * Math.PI / 180), i.drawImage(t, -n, -a), e
    }

    function scaleRect(t, e) {
        return {
            x: t.x * e,
            y: t.y * e,
            width: t.width * e,
            height: t.height * e
        }
    }

    function roundRect(t) {
        return {
            x: Math.floor(t.x),
            y: Math.floor(t.y),
            width: Math.floor(t.width),
            height: Math.floor(t.height)
        }
    }

    function limit(t, e, i) {
        return Math.min(Math.max(t, e), i)
    }

    function clone(t) {
        return JSON.parse(JSON.stringify(t))
    }

    function cloneData(t) {
        var e = cloneCanvas(t.input.image),
            i = cloneCanvas(t.output.image),
            n = clone(t);
        return n.input.image = e, n.output.image = i, n
    }

    function getMimeTypeFromDataURI(t) {
        if (!t) return null;
        var e = t.match(/^.+;/);
        return e.length ? e[0].substring(5, e[0].length - 1) : null
    }

    function flattenData(t) {
        var e = arguments.length <= 1 || void 0 === arguments[1] ? [] : arguments[1],
            i = null,
            n = {
                input: {
                    name: t.input.name,
                    type: t.input.type,
                    size: t.input.size,
                    width: t.input.width,
                    height: t.input.height
                },
                output: {
                    width: t.output.width,
                    height: t.output.height
                }
            };
        if (inArray("input", e) && (n.input.image = t.input.image.toDataURL(t.input.type, 1)), inArray("output", e) && (n.output.image = t.output.image.toDataURL(t.input.type, 1)), inArray("actions", e) && (n.actions = clone(t.actions)), i = getMimeTypeFromDataURI(n.output.image || n.input.image), "image/png" === i) {
            var a = n.input.name;
            n.input.name = a.substr(0, a.lastIndexOf(".")) + ".png", n.input.type = i
        }
        return n
    }

    function toggleDisplayBySelector(t, e, i) {
        var n = i.querySelector(t);
        n && (n.style.display = e ? "" : "none")
    }

    function getAttributeAsInt(t, e) {
        return parseInt(t.getAttribute(e), 10)
    }

    function nodeListToArray(t) {
        return Array.prototype.slice.call(t)
    }

    function removeElement(t) {
        t.parentNode.removeChild(t)
    }

    function wrap(t) {
        var e = create("div");
        return t.parentNode && (t.nextSibling ? t.parentNode.insertBefore(e, t.nextSibling) : t.parentNode.appendChild(e)), e.appendChild(t), e
    }

    function polarToCartesian(t, e, i, n) {
        var a = (n - 90) * Math.PI / 180;
        return {
            x: t + i * Math.cos(a),
            y: e + i * Math.sin(a)
        }
    }

    function describeArc(t, e, i, n, a) {
        var o = polarToCartesian(t, e, i, a),
            r = polarToCartesian(t, e, i, n),
            s = 180 >= a - n ? "0" : "1",
            l = ["M", o.x, o.y, "A", i, i, 0, s, 0, r.x, r.y].join(" ");
        return l
    }

    function percentageArc(t, e, i, n) {
        return describeArc(t, e, i, 0, 360 * n)
    }

    function intSplit(t, e) {
        return t.split(e).map(function(t) {
            return parseInt(t, 10)
        })
    }

    function isWrapper(t) {
        return "DIV" == t.nodeName
    }
    var loadImage = function(t, e, i) {
            var n, a, o = document.createElement("img");
            if (o.onerror = e, o.onload = function() {
                    !a || i && i.noRevoke || loadImage.revokeObjectURL(a), e && e(loadImage.scale(o, i))
                }, loadImage.isInstanceOf("Blob", t) || loadImage.isInstanceOf("File", t)) n = a = loadImage.createObjectURL(t), o._type = t.type;
            else {
                if ("string" != typeof t) return !1;
                n = t, i && i.crossOrigin && (o.crossOrigin = i.crossOrigin)
            }
            return n ? (o.src = n, o) : loadImage.readFile(t, function(t) {
                var i = t.target;
                i && i.result ? o.src = i.result : e && e(t)
            })
        },
        urlAPI = window.createObjectURL && window || window.URL && URL.revokeObjectURL && URL || window.webkitURL && webkitURL;
    loadImage.isInstanceOf = function(t, e) {
        return Object.prototype.toString.call(e) === "[object " + t + "]"
    }, loadImage.transformCoordinates = function() {}, loadImage.getTransformedOptions = function(t, e) {
        var i, n, a, o, r = e.aspectRatio;
        if (!r) return e;
        i = {};
        for (n in e) e.hasOwnProperty(n) && (i[n] = e[n]);
        return i.crop = !0, a = t.naturalWidth || t.width, o = t.naturalHeight || t.height, a / o > r ? (i.maxWidth = o * r, i.maxHeight = o) : (i.maxWidth = a, i.maxHeight = a / r), i
    }, loadImage.renderImageToCanvas = function(t, e, i, n, a, o, r, s, l, h) {
        return t.getContext("2d").drawImage(e, i, n, a, o, r, s, l, h), t
    }, loadImage.hasCanvasOption = function(t) {
        return t.canvas || t.crop || !!t.aspectRatio
    }, loadImage.scale = function(t, e) {
        function i() {
            var t = Math.max((r || y) / y, (s || w) / w);
            t > 1 && (y *= t, w *= t)
        }

        function n() {
            var t = Math.min((a || y) / y, (o || w) / w);
            1 > t && (y *= t, w *= t)
        }
        e = e || {};
        var a, o, r, s, l, h, u, c, d, p, m, f = document.createElement("canvas"),
            g = t.getContext || loadImage.hasCanvasOption(e) && f.getContext,
            v = t.naturalWidth || t.width,
            _ = t.naturalHeight || t.height,
            y = v,
            w = _;
        if (g && (e = loadImage.getTransformedOptions(t, e), u = e.left || 0, c = e.top || 0, e.sourceWidth ? (l = e.sourceWidth, void 0 !== e.right && void 0 === e.left && (u = v - l - e.right)) : l = v - u - (e.right || 0), e.sourceHeight ? (h = e.sourceHeight, void 0 !== e.bottom && void 0 === e.top && (c = _ - h - e.bottom)) : h = _ - c - (e.bottom || 0), y = l, w = h), a = e.maxWidth, o = e.maxHeight, r = e.minWidth, s = e.minHeight, g && a && o && e.crop ? (y = a, w = o, m = l / h - a / o, 0 > m ? (h = o * l / a, void 0 === e.top && void 0 === e.bottom && (c = (_ - h) / 2)) : m > 0 && (l = a * h / o, void 0 === e.left && void 0 === e.right && (u = (v - l) / 2))) : ((e.contain || e.cover) && (r = a = a || r, s = o = o || s), e.cover ? (n(), i()) : (i(), n())), g) {
            if (d = e.pixelRatio, d > 1 && (f.style.width = y + "px", f.style.height = w + "px", y *= d, w *= d, f.getContext("2d").scale(d, d)), p = e.downsamplingRatio, p > 0 && 1 > p && l > y && h > w)
                for (; l * p > y;) f.width = l * p, f.height = h * p, loadImage.renderImageToCanvas(f, t, u, c, l, h, 0, 0, f.width, f.height), l = f.width, h = f.height, t = document.createElement("canvas"), t.width = l, t.height = h, loadImage.renderImageToCanvas(t, f, 0, 0, l, h, 0, 0, l, h);
            return f.width = y, f.height = w, loadImage.transformCoordinates(f, e), loadImage.renderImageToCanvas(f, t, u, c, l, h, 0, 0, y, w)
        }
        return t.width = y, t.height = w, t
    }, loadImage.createObjectURL = function(t) {
        return urlAPI ? urlAPI.createObjectURL(t) : !1
    }, loadImage.revokeObjectURL = function(t) {
        return urlAPI ? urlAPI.revokeObjectURL(t) : !1
    }, loadImage.readFile = function(t, e, i) {
        if (window.FileReader) {
            var n = new FileReader;
            if (n.onload = n.onerror = e, i = i || "readAsDataURL", n[i]) return n[i](t), n
        }
        return !1
    };
    var originalHasCanvasOption = loadImage.hasCanvasOption,
        originalTransformCoordinates = loadImage.transformCoordinates,
        originalGetTransformedOptions = loadImage.getTransformedOptions;
    loadImage.hasCanvasOption = function(t) {
        return !!t.orientation || originalHasCanvasOption.call(loadImage, t)
    }, loadImage.transformCoordinates = function(t, e) {
        originalTransformCoordinates.call(loadImage, t, e);
        var i = t.getContext("2d"),
            n = t.width,
            a = t.height,
            o = t.style.width,
            r = t.style.height,
            s = e.orientation;
        if (s && !(s > 8)) switch (s > 4 && (t.width = a, t.height = n, t.style.width = r, t.style.height = o), s) {
            case 2:
                i.translate(n, 0), i.scale(-1, 1);
                break;
            case 3:
                i.translate(n, a), i.rotate(Math.PI);
                break;
            case 4:
                i.translate(0, a), i.scale(1, -1);
                break;
            case 5:
                i.rotate(.5 * Math.PI), i.scale(1, -1);
                break;
            case 6:
                i.rotate(.5 * Math.PI), i.translate(0, -a);
                break;
            case 7:
                i.rotate(.5 * Math.PI), i.translate(n, -a), i.scale(-1, 1);
                break;
            case 8:
                i.rotate(-.5 * Math.PI), i.translate(-n, 0)
        }
    }, loadImage.getTransformedOptions = function(t, e) {
        var i, n, a = originalGetTransformedOptions.call(loadImage, t, e),
            o = a.orientation;
        if (!o || o > 8 || 1 === o) return a;
        i = {};
        for (n in a) a.hasOwnProperty(n) && (i[n] = a[n]);
        switch (a.orientation) {
            case 2:
                i.left = a.right, i.right = a.left;
                break;
            case 3:
                i.left = a.right, i.top = a.bottom, i.right = a.left, i.bottom = a.top;
                break;
            case 4:
                i.top = a.bottom, i.bottom = a.top;
                break;
            case 5:
                i.left = a.top, i.top = a.left, i.right = a.bottom, i.bottom = a.right;
                break;
            case 6:
                i.left = a.top, i.top = a.right, i.right = a.bottom, i.bottom = a.left;
                break;
            case 7:
                i.left = a.bottom, i.top = a.right, i.right = a.top, i.bottom = a.left;
                break;
            case 8:
                i.left = a.bottom, i.top = a.left, i.right = a.top, i.bottom = a.right
        }
        return a.orientation > 4 && (i.maxWidth = a.maxHeight, i.maxHeight = a.maxWidth, i.minWidth = a.minHeight, i.minHeight = a.minWidth, i.sourceWidth = a.sourceHeight, i.sourceHeight = a.sourceWidth), i
    };
    var hasblobSlice = window.Blob && (Blob.prototype.slice || Blob.prototype.webkitSlice || Blob.prototype.mozSlice);
    loadImage.blobSlice = hasblobSlice && function() {
            var t = this.slice || this.webkitSlice || this.mozSlice;
            return t.apply(this, arguments)
        }, loadImage.metaDataParsers = {
        jpeg: {
            65505: []
        }
    }, loadImage.parseMetaData = function(t, e, i) {
        i = i || {};
        var n = this,
            a = i.maxMetaDataSize || 262144,
            o = {},
            r = !(window.DataView && t && t.size >= 12 && "image/jpeg" === t.type && loadImage.blobSlice);
        !r && loadImage.readFile(loadImage.blobSlice.call(t, 0, a), function(t) {
            if (t.target.error) return console.log(t.target.error), void e(o);
            var a, r, s, l, h = t.target.result,
                u = new DataView(h),
                c = 2,
                d = u.byteLength - 4,
                p = c;
            if (65496 === u.getUint16(0)) {
                for (; d > c && (a = u.getUint16(c), a >= 65504 && 65519 >= a || 65534 === a);) {
                    if (r = u.getUint16(c + 2) + 2, c + r > u.byteLength) {
                        console.log("Invalid meta data: Invalid segment size.");
                        break
                    }
                    if (s = loadImage.metaDataParsers.jpeg[a])
                        for (l = 0; l < s.length; l += 1) s[l].call(n, u, c, r, o, i);
                    c += r, p = c
                }!i.disableImageHead && p > 6 && (h.slice ? o.imageHead = h.slice(0, p) : o.imageHead = new Uint8Array(h).subarray(0, p))
            } else console.log("Invalid JPEG file: Missing JPEG marker.");
            e(o)
        }, "readAsArrayBuffer") || e(o)
    }, loadImage.ExifMap = function() {
        return this
    }, loadImage.ExifMap.prototype.map = {
        Orientation: 274
    }, loadImage.ExifMap.prototype.get = function(t) {
        return this[t] || this[this.map[t]]
    }, loadImage.getExifThumbnail = function(t, e, i) {
        var n, a, o;
        if (!i || e + i > t.byteLength) return void console.log("Invalid Exif data: Invalid thumbnail data.");
        for (n = [], a = 0; i > a; a += 1) o = t.getUint8(e + a), n.push((16 > o ? "0" : "") + o.toString(16));
        return "data:image/jpeg,%" + n.join("%")
    }, loadImage.exifTagTypes = {
        1: {
            getValue: function(t, e) {
                return t.getUint8(e)
            },
            size: 1
        },
        2: {
            getValue: function(t, e) {
                return String.fromCharCode(t.getUint8(e))
            },
            size: 1,
            ascii: !0
        },
        3: {
            getValue: function(t, e, i) {
                return t.getUint16(e, i)
            },
            size: 2
        },
        4: {
            getValue: function(t, e, i) {
                return t.getUint32(e, i)
            },
            size: 4
        },
        5: {
            getValue: function(t, e, i) {
                return t.getUint32(e, i) / t.getUint32(e + 4, i)
            },
            size: 8
        },
        9: {
            getValue: function(t, e, i) {
                return t.getInt32(e, i)
            },
            size: 4
        },
        10: {
            getValue: function(t, e, i) {
                return t.getInt32(e, i) / t.getInt32(e + 4, i)
            },
            size: 8
        }
    }, loadImage.exifTagTypes[7] = loadImage.exifTagTypes[1], loadImage.getExifValue = function(t, e, i, n, a, o) {
        var r, s, l, h, u, c, d = loadImage.exifTagTypes[n];
        if (!d) return void console.log("Invalid Exif data: Invalid tag type.");
        if (r = d.size * a, s = r > 4 ? e + t.getUint32(i + 8, o) : i + 8, s + r > t.byteLength) return void console.log("Invalid Exif data: Invalid data offset.");
        if (1 === a) return d.getValue(t, s, o);
        for (l = [], h = 0; a > h; h += 1) l[h] = d.getValue(t, s + h * d.size, o);
        if (d.ascii) {
            for (u = "", h = 0; h < l.length && (c = l[h], "\x00" !== c); h += 1) u += c;
            return u
        }
        return l
    }, loadImage.parseExifTag = function(t, e, i, n, a) {
        var o = t.getUint16(i, n);
        a.exif[o] = loadImage.getExifValue(t, e, i, t.getUint16(i + 2, n), t.getUint32(i + 4, n), n)
    }, loadImage.parseExifTags = function(t, e, i, n, a) {
        var o, r, s;
        if (i + 6 > t.byteLength) return void console.log("Invalid Exif data: Invalid directory offset.");
        if (o = t.getUint16(i, n), r = i + 2 + 12 * o, r + 4 > t.byteLength) return void console.log("Invalid Exif data: Invalid directory size.");
        for (s = 0; o > s; s += 1) this.parseExifTag(t, e, i + 2 + 12 * s, n, a);
        return t.getUint32(r, n)
    }, loadImage.parseExifData = function(t, e, i, n, a) {
        if (!a.disableExif) {
            var o, r, s, l = e + 10;
            if (1165519206 === t.getUint32(e + 4)) {
                if (l + 8 > t.byteLength) return void console.log("Invalid Exif data: Invalid segment size.");
                if (0 !== t.getUint16(e + 8)) return void console.log("Invalid Exif data: Missing byte alignment offset.");
                switch (t.getUint16(l)) {
                    case 18761:
                        o = !0;
                        break;
                    case 19789:
                        o = !1;
                        break;
                    default:
                        return void console.log("Invalid Exif data: Invalid byte alignment marker.")
                }
                if (42 !== t.getUint16(l + 2, o)) return void console.log("Invalid Exif data: Missing TIFF marker.");
                r = t.getUint32(l + 4, o), n.exif = new loadImage.ExifMap, r = loadImage.parseExifTags(t, l, l + r, o, n), r && !a.disableExifThumbnail && (s = {
                    exif: {}
                }, r = loadImage.parseExifTags(t, l, l + r, o, s), s.exif[513] && (n.exif.Thumbnail = loadImage.getExifThumbnail(t, l + s.exif[513], s.exif[514]))), n.exif[34665] && !a.disableExifSub && loadImage.parseExifTags(t, l, l + n.exif[34665], o, n), n.exif[34853] && !a.disableExifGps && loadImage.parseExifTags(t, l, l + n.exif[34853], o, n)
            }
        }
    }, loadImage.metaDataParsers.jpeg[65505].push(loadImage.parseExifData);
    var snabbt = function() {
            var t = [],
                e = [],
                i = [],
                n = "transform",
                a = window.getComputedStyle(document.documentElement, ""),
                o = (Array.prototype.slice.call(a).join("").match(/-(moz|webkit|ms)-/) || "" === a.OLink && ["", "o"])[1];
            "webkit" === o && (n = "webkitTransform");
            var r = function(t, e, i) {
                    var n = t;
                    if (void 0 !== n.length) {
                        for (var a = {
                            chainers: [],
                            then: function(t) {
                                return this.snabbt(t)
                            },
                            snabbt: function(t) {
                                var e = this.chainers.length;
                                return this.chainers.forEach(function(i, n) {
                                    i.snabbt(s(t, n, e))
                                }), a
                            },
                            setValue: function(t) {
                                return this.chainers.forEach(function(e) {
                                    e.setValue(t)
                                }), a
                            },
                            finish: function() {
                                return this.chainers.forEach(function(t) {
                                    t.finish()
                                }), a
                            },
                            rollback: function() {
                                return this.chainers.forEach(function(t) {
                                    t.rollback()
                                }), a
                            }
                        }, o = 0, r = n.length; r > o; ++o) "string" == typeof e ? a.chainers.push(l(n[o], e, s(i, o, r))) : a.chainers.push(l(n[o], s(e, o, r), i));
                        return a
                    }
                    return "string" == typeof e ? l(n, e, s(i, 0, 1)) : l(n, s(e, 0, 1), i)
                },
                s = function(t, e, i) {
                    if (!t) return t;
                    var n = V(t);
                    G(t.delay) && (n.delay = t.delay(e, i)), G(t.callback) && (n.complete = function() {
                        t.callback.call(this, e, i)
                    });
                    var a = G(t.allDone),
                        o = G(t.complete);
                    (o || a) && (n.complete = function() {
                        o && t.complete.call(this, e, i), a && e == i - 1 && t.allDone()
                    }), G(t.valueFeeder) && (n.valueFeeder = function(n, a) {
                        return t.valueFeeder(n, a, e, i)
                    }), G(t.easing) && (n.easing = function(n) {
                        return t.easing(n, e, i)
                    });
                    var r = ["position", "rotation", "skew", "rotationPost", "scale", "width", "height", "opacity", "fromPosition", "fromRotation", "fromSkew", "fromRotationPost", "fromScale", "fromWidth", "fromHeight", "fromOpacity", "transformOrigin", "duration", "delay"];
                    return r.forEach(function(a) {
                        G(t[a]) && (n[a] = t[a](e, i))
                    }), n
                },
                l = function(t, i, n) {
                    function a(i) {
                        return v.tick(i), v.updateElement(t), v.isStopped() ? void 0 : v.completed() ? void(o.loop > 1 && !v.isStopped() ? (o.loop -= 1, v.restart(), _(a)) : (o.complete && o.complete.call(t), y.length && (o = y.pop(), l = f(o, c, !0), c = f(o, V(c)), o = g(l, c, o), v = w(o), e.push([t, v]), v.tick(i), _(a)))) : _(a)
                    }
                    if ("attention" === i) return h(t, n);
                    if ("stop" === i) return u(t);
                    var o = i;
                    d();
                    var r = m(t),
                        l = r;
                    l = f(o, l, !0);
                    var c = V(r);
                    c = f(o, c);
                    var p = g(l, c, o),
                        v = w(p);
                    e.push([t, v]), v.updateElement(t, !0);
                    var y = [],
                        b = {
                            snabbt: function(t) {
                                return y.unshift(s(t, 0, 1)), b
                            },
                            then: function(t) {
                                return this.snabbt(t)
                            }
                        };
                    return _(a), o.manual ? v : b
                },
                h = function(t, i) {
                    function n(e) {
                        o.tick(e), o.updateElement(t), o.completed() ? (i.callback && i.callback(t), i.loop && i.loop > 1 && (i.loop--, o.restart(), _(n))) : _(n)
                    }
                    var a = f(i, U({}));
                    i.movement = a;
                    var o = b(i);
                    e.push([t, o]), _(n)
                },
                u = function(t) {
                    for (var i = 0, n = e.length; n > i; ++i) {
                        var a = e[i],
                            o = a[0],
                            r = a[1];
                        o === t && r.stop()
                    }
                },
                c = function(t, e) {
                    for (var i = 0, n = t.length; n > i; ++i) {
                        var a = t[i],
                            o = a[0],
                            r = a[1];
                        if (o === e) {
                            var s = r.getCurrentState();
                            return r.stop(), s
                        }
                    }
                },
                d = function() {
                    i = i.filter(function(t) {
                        return p(t[0]).body
                    })
                },
                p = function(t) {
                    for (var e = t; e.parentNode;) e = e.parentNode;
                    return e
                },
                m = function(t) {
                    var n = c(e, t);
                    return n ? n : c(i, t)
                },
                f = function(t, e, i) {
                    e || (e = U({
                        position: [0, 0, 0],
                        rotation: [0, 0, 0],
                        rotationPost: [0, 0, 0],
                        scale: [1, 1],
                        skew: [0, 0]
                    }));
                    var n = "position",
                        a = "rotation",
                        o = "skew",
                        r = "rotationPost",
                        s = "scale",
                        l = "scalePost",
                        h = "width",
                        u = "height",
                        c = "opacity";
                    return i && (n = "fromPosition", a = "fromRotation", o = "fromSkew", r = "fromRotationPost", s = "fromScale", l = "fromScalePost", h = "fromWidth", u = "fromHeight", c = "fromOpacity"), e.position = W(t[n], e.position), e.rotation = W(t[a], e.rotation), e.rotationPost = W(t[r], e.rotationPost), e.skew = W(t[o], e.skew), e.scale = W(t[s], e.scale), e.scalePost = W(t[l], e.scalePost), e.opacity = t[c], e.width = t[h], e.height = t[u], e
                },
                g = function(t, e, i) {
                    return i.startState = t, i.endState = e, i
                },
                v = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || function(t) {
                        return setTimeout(t, 1e3 / 60)
                    },
                _ = function(e) {
                    0 === t.length && v(y), t.push(e)
                },
                y = function(n) {
                    for (var a = t.length, o = 0; a > o; ++o) t[o](n);
                    t.splice(0, a);
                    var r = e.filter(function(t) {
                        return t[1].completed()
                    });
                    i = i.filter(function(t) {
                        for (var e = 0, i = r.length; i > e; ++e)
                            if (t[0] === r[e][0]) return !1;
                        return !0
                    }), i = i.concat(r), e = e.filter(function(t) {
                        return !t[1].completed()
                    }), 0 !== t.length && v(y)
                },
                w = function(t) {
                    var e = t.startState,
                        i = t.endState,
                        n = W(t.duration, 500),
                        a = W(t.delay, 0),
                        o = t.perspective,
                        r = T(W(t.easing, "linear"), t),
                        s = 0 === n ? i.clone() : e.clone();
                    t.transformOrigin;
                    s.transformOrigin = t.transformOrigin;
                    var l, h, u = 0,
                        c = 0,
                        d = !1,
                        p = !1,
                        m = t.manual,
                        f = 0,
                        g = a / n;
                    return h = t.valueFeeder ? N(t.valueFeeder, e, i, s) : B(e, i, s), {
                        stop: function() {
                            d = !0
                        },
                        isStopped: function() {
                            return d
                        },
                        finish: function(t) {
                            m = !1;
                            var e = n * f;
                            u = c - e, l = t, r.resetFrom = f
                        },
                        rollback: function(t) {
                            m = !1, h.setReverse();
                            var e = n * (1 - f);
                            u = c - e, l = t, r.resetFrom = f
                        },
                        restart: function() {
                            u = void 0, r.resetFrom(0)
                        },
                        tick: function(t) {
                            if (!d) {
                                if (m) return c = t, void this.updateCurrentTransform();
                                if (u || (u = t), t - u > a) {
                                    p = !0, c = t - a;
                                    var e = Math.min(Math.max(0, c - u), n);
                                    r.tick(e / n), this.updateCurrentTransform(), this.completed() && l && l()
                                }
                            }
                        },
                        getCurrentState: function() {
                            return s
                        },
                        setValue: function(t) {
                            p = !0, f = Math.min(Math.max(t, 1e-4), 1 + g)
                        },
                        updateCurrentTransform: function() {
                            var t = r.getValue();
                            if (m) {
                                var e = Math.max(1e-5, f - g);
                                r.tick(e), t = r.getValue()
                            }
                            h.tween(t)
                        },
                        completed: function() {
                            return d ? !0 : 0 === u ? !1 : r.completed()
                        },
                        updateElement: function(t, e) {
                            if (p || e) {
                                var i = h.asMatrix(),
                                    n = h.getProperties();
                                q(t, i, o), j(t, n)
                            }
                        }
                    }
                },
                b = function(t) {
                    var e = t.movement;
                    t.initialVelocity = .1, t.equilibriumPosition = 0;
                    var i = S(t),
                        n = !1,
                        a = e.position,
                        o = e.rotation,
                        r = e.rotationPost,
                        s = e.scale,
                        l = e.skew,
                        h = U({
                            position: a ? [0, 0, 0] : void 0,
                            rotation: o ? [0, 0, 0] : void 0,
                            rotationPost: r ? [0, 0, 0] : void 0,
                            scale: s ? [0, 0] : void 0,
                            skew: l ? [0, 0] : void 0
                        });
                    return {
                        stop: function() {
                            n = !0
                        },
                        isStopped: function(t) {
                            return n
                        },
                        tick: function(t) {
                            n || i.equilibrium || (i.tick(), this.updateMovement())
                        },
                        updateMovement: function() {
                            var t = i.getValue();
                            a && (h.position[0] = e.position[0] * t, h.position[1] = e.position[1] * t, h.position[2] = e.position[2] * t), o && (h.rotation[0] = e.rotation[0] * t, h.rotation[1] = e.rotation[1] * t, h.rotation[2] = e.rotation[2] * t), r && (h.rotationPost[0] = e.rotationPost[0] * t, h.rotationPost[1] = e.rotationPost[1] * t, h.rotationPost[2] = e.rotationPost[2] * t), s && (h.scale[0] = 1 + e.scale[0] * t, h.scale[1] = 1 + e.scale[1] * t), l && (h.skew[0] = e.skew[0] * t, h.skew[1] = e.skew[1] * t)
                        },
                        updateElement: function(t) {
                            q(t, h.asMatrix()), j(t, h.getProperties())
                        },
                        getCurrentState: function() {
                            return h
                        },
                        completed: function() {
                            return i.equilibrium || n
                        },
                        restart: function() {
                            i = S(t)
                        }
                    }
                },
                k = function(t) {
                    return t
                },
                E = function(t) {
                    return (Math.cos(t * Math.PI + Math.PI) + 1) / 2
                },
                C = function(t) {
                    return t * t
                },
                x = function(t) {
                    return -Math.pow(t - 1, 2) + 1
                },
                S = function(t) {
                    var e = W(t.startPosition, 0),
                        i = W(t.equilibriumPosition, 1),
                        n = W(t.initialVelocity, 0),
                        a = W(t.springConstant, .8),
                        o = W(t.springDeceleration, .9),
                        r = W(t.springMass, 10),
                        s = !1;
                    return {
                        tick: function(t) {
                            if (0 !== t && !s) {
                                var l = -(e - i) * a,
                                    h = l / r;
                                n += h, e += n, n *= o, Math.abs(e - i) < .001 && Math.abs(n) < .001 && (s = !0)
                            }
                        },
                        resetFrom: function(t) {
                            e = t, n = 0
                        },
                        getValue: function() {
                            return s ? i : e
                        },
                        completed: function() {
                            return s
                        }
                    }
                },
                I = {
                    linear: k,
                    ease: E,
                    easeIn: C,
                    easeOut: x
                },
                T = function(t, e) {
                    if ("spring" == t) return S(e);
                    var i = t;
                    G(t) || (i = I[t]);
                    var n, a = i,
                        o = 0;
                    return {
                        tick: function(t) {
                            o = a(t), n = t
                        },
                        resetFrom: function(t) {
                            n = 0
                        },
                        getValue: function() {
                            return o
                        },
                        completed: function() {
                            return n >= 1 ? n : !1
                        }
                    }
                },
                P = function(t, e, i, n) {
                    t[0] = 1, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = 1, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = e, t[13] = i, t[14] = n, t[15] = 1
                },
                O = function(t, e) {
                    t[0] = 1, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = Math.cos(e), t[6] = -Math.sin(e), t[7] = 0, t[8] = 0, t[9] = Math.sin(e), t[10] = Math.cos(e), t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                M = function(t, e) {
                    t[0] = Math.cos(e), t[1] = 0, t[2] = Math.sin(e), t[3] = 0, t[4] = 0, t[5] = 1, t[6] = 0, t[7] = 0, t[8] = -Math.sin(e), t[9] = 0, t[10] = Math.cos(e), t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                L = function(t, e) {
                    t[0] = Math.cos(e), t[1] = -Math.sin(e), t[2] = 0, t[3] = 0, t[4] = Math.sin(e), t[5] = Math.cos(e), t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                D = function(t, e, i) {
                    t[0] = 1, t[1] = Math.tan(e), t[2] = 0, t[3] = 0, t[4] = Math.tan(i), t[5] = 1, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                A = function(t, e, i) {
                    t[0] = e, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = i, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                F = function(t) {
                    t[0] = 1, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = 1, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                R = function(t, e) {
                    e[0] = t[0], e[1] = t[1], e[2] = t[2], e[3] = t[3], e[4] = t[4], e[5] = t[5], e[6] = t[6], e[7] = t[7], e[8] = t[8], e[9] = t[9], e[10] = t[10], e[11] = t[11], e[12] = t[12], e[13] = t[13], e[14] = t[14], e[15] = t[15]
                },
                z = function() {
                    var t = new Float32Array(16),
                        e = new Float32Array(16),
                        i = new Float32Array(16);
                    return F(t), {
                        data: t,
                        asCSS: function() {
                            for (var e = "matrix3d(", i = 0; 15 > i; ++i) e += Math.abs(t[i]) < 1e-4 ? "0," : t[i].toFixed(10) + ",";
                            return e += Math.abs(t[15]) < 1e-4 ? "0)" : t[15].toFixed(10) + ")"
                        },
                        clear: function() {
                            F(t)
                        },
                        translate: function(n, a, o) {
                            return R(t, e), P(i, n, a, o), H(e, i, t), this
                        },
                        rotateX: function(n) {
                            return R(t, e), O(i, n), H(e, i, t), this
                        },
                        rotateY: function(n) {
                            return R(t, e), M(i, n), H(e, i, t), this
                        },
                        rotateZ: function(n) {
                            return R(t, e), L(i, n), H(e, i, t), this
                        },
                        scale: function(n, a) {
                            return R(t, e), A(i, n, a), H(e, i, t), this
                        },
                        skew: function(n, a) {
                            return R(t, e), D(i, n, a), H(e, i, t), this
                        }
                    }
                },
                H = function(t, e, i) {
                    return i[0] = t[0] * e[0] + t[1] * e[4] + t[2] * e[8] + t[3] * e[12], i[1] = t[0] * e[1] + t[1] * e[5] + t[2] * e[9] + t[3] * e[13], i[2] = t[0] * e[2] + t[1] * e[6] + t[2] * e[10] + t[3] * e[14], i[3] = t[0] * e[3] + t[1] * e[7] + t[2] * e[11] + t[3] * e[15], i[4] = t[4] * e[0] + t[5] * e[4] + t[6] * e[8] + t[7] * e[12], i[5] = t[4] * e[1] + t[5] * e[5] + t[6] * e[9] + t[7] * e[13], i[6] = t[4] * e[2] + t[5] * e[6] + t[6] * e[10] + t[7] * e[14], i[7] = t[4] * e[3] + t[5] * e[7] + t[6] * e[11] + t[7] * e[15], i[8] = t[8] * e[0] + t[9] * e[4] + t[10] * e[8] + t[11] * e[12], i[9] = t[8] * e[1] + t[9] * e[5] + t[10] * e[9] + t[11] * e[13], i[10] = t[8] * e[2] + t[9] * e[6] + t[10] * e[10] + t[11] * e[14], i[11] = t[8] * e[3] + t[9] * e[7] + t[10] * e[11] + t[11] * e[15], i[12] = t[12] * e[0] + t[13] * e[4] + t[14] * e[8] + t[15] * e[12], i[13] = t[12] * e[1] + t[13] * e[5] + t[14] * e[9] + t[15] * e[13], i[14] = t[12] * e[2] + t[13] * e[6] + t[14] * e[10] + t[15] * e[14], i[15] = t[12] * e[3] + t[13] * e[7] + t[14] * e[11] + t[15] * e[15], i
                },
                U = function(t) {
                    var e = z(),
                        i = {
                            opacity: void 0,
                            width: void 0,
                            height: void 0
                        };
                    return {
                        position: t.position,
                        rotation: t.rotation,
                        rotationPost: t.rotationPost,
                        skew: t.skew,
                        scale: t.scale,
                        scalePost: t.scalePost,
                        opacity: t.opacity,
                        width: t.width,
                        height: t.height,
                        clone: function() {
                            return U({
                                position: this.position ? this.position.slice(0) : void 0,
                                rotation: this.rotation ? this.rotation.slice(0) : void 0,
                                rotationPost: this.rotationPost ? this.rotationPost.slice(0) : void 0,
                                skew: this.skew ? this.skew.slice(0) : void 0,
                                scale: this.scale ? this.scale.slice(0) : void 0,
                                scalePost: this.scalePost ? this.scalePost.slice(0) : void 0,
                                height: this.height,
                                width: this.width,
                                opacity: this.opacity
                            })
                        },
                        asMatrix: function() {
                            var t = e;
                            return t.clear(), this.transformOrigin && t.translate(-this.transformOrigin[0], -this.transformOrigin[1], -this.transformOrigin[2]), this.scale && t.scale(this.scale[0], this.scale[1]), this.skew && t.skew(this.skew[0], this.skew[1]), this.rotation && (t.rotateX(this.rotation[0]), t.rotateY(this.rotation[1]), t.rotateZ(this.rotation[2])), this.position && t.translate(this.position[0], this.position[1], this.position[2]), this.rotationPost && (t.rotateX(this.rotationPost[0]), t.rotateY(this.rotationPost[1]), t.rotateZ(this.rotationPost[2])), this.scalePost && t.scale(this.scalePost[0], this.scalePost[1]), this.transformOrigin && t.translate(this.transformOrigin[0], this.transformOrigin[1], this.transformOrigin[2]), t
                        },
                        getProperties: function() {
                            return i.opacity = this.opacity, i.width = this.width + "px", i.height = this.height + "px", i
                        }
                    }
                },
                B = function(t, e, i) {
                    var n = t,
                        a = e,
                        o = i,
                        r = void 0 !== a.position,
                        s = void 0 !== a.rotation,
                        l = void 0 !== a.rotationPost,
                        h = void 0 !== a.scale,
                        u = void 0 !== a.skew,
                        c = void 0 !== a.width,
                        d = void 0 !== a.height,
                        p = void 0 !== a.opacity;
                    return {
                        tween: function(t) {
                            if (r) {
                                var e = a.position[0] - n.position[0],
                                    i = a.position[1] - n.position[1],
                                    m = a.position[2] - n.position[2];
                                o.position[0] = n.position[0] + t * e, o.position[1] = n.position[1] + t * i, o.position[2] = n.position[2] + t * m
                            }
                            if (s) {
                                var f = a.rotation[0] - n.rotation[0],
                                    g = a.rotation[1] - n.rotation[1],
                                    v = a.rotation[2] - n.rotation[2];
                                o.rotation[0] = n.rotation[0] + t * f, o.rotation[1] = n.rotation[1] + t * g, o.rotation[2] = n.rotation[2] + t * v
                            }
                            if (l) {
                                var _ = a.rotationPost[0] - n.rotationPost[0],
                                    y = a.rotationPost[1] - n.rotationPost[1],
                                    w = a.rotationPost[2] - n.rotationPost[2];
                                o.rotationPost[0] = n.rotationPost[0] + t * _, o.rotationPost[1] = n.rotationPost[1] + t * y, o.rotationPost[2] = n.rotationPost[2] + t * w
                            }
                            if (u) {
                                var b = a.scale[0] - n.scale[0],
                                    k = a.scale[1] - n.scale[1];
                                o.scale[0] = n.scale[0] + t * b, o.scale[1] = n.scale[1] + t * k
                            }
                            if (h) {
                                var E = a.skew[0] - n.skew[0],
                                    C = a.skew[1] - n.skew[1];
                                o.skew[0] = n.skew[0] + t * E, o.skew[1] = n.skew[1] + t * C
                            }
                            if (c) {
                                var x = a.width - n.width;
                                o.width = n.width + t * x
                            }
                            if (d) {
                                var S = a.height - n.height;
                                o.height = n.height + t * S
                            }
                            if (p) {
                                var I = a.opacity - n.opacity;
                                o.opacity = n.opacity + t * I
                            }
                        },
                        asMatrix: function() {
                            return o.asMatrix()
                        },
                        getProperties: function() {
                            return o.getProperties()
                        },
                        setReverse: function() {
                            var t = n;
                            n = a, a = t
                        }
                    }
                },
                N = function(t, e, i, n) {
                    var a = t(0, z()),
                        o = e,
                        r = i,
                        s = n,
                        l = !1;
                    return {
                        tween: function(e) {
                            l && (e = 1 - e), a.clear(), a = t(e, a);
                            var i = r.width - o.width,
                                n = r.height - o.height,
                                h = r.opacity - o.opacity;
                            void 0 !== r.width && (s.width = o.width + e * i), void 0 !== r.height && (s.height = o.height + e * n), void 0 !== r.opacity && (s.opacity = o.opacity + e * h)
                        },
                        asMatrix: function() {
                            return a
                        },
                        getProperties: function() {
                            return s.getProperties()
                        },
                        setReverse: function() {
                            l = !0
                        }
                    }
                },
                W = function(t, e) {
                    return "undefined" == typeof t ? e : t
                },
                q = function(t, e, i) {
                    var a = "";
                    i && (a = "perspective(" + i + "px) ");
                    var o = e.asCSS();
                    t.style[n] = a + o
                },
                j = function(t, e) {
                    for (var i in e) t.style[i] = e[i]
                },
                G = function(t) {
                    return "function" == typeof t
                },
                V = function(t) {
                    if (!t) return t;
                    var e = {};
                    for (var i in t) e[i] = t[i];
                    return e
                };
            return r.createMatrix = z, r.setElementTransform = q, r
        }(),
        stackBlur = function() {
            function t(t, e, i, n, a) {
                if ("string" == typeof t) t = document.getElementById(t);
                else if (!t instanceof HTMLCanvasElement) return;
                var o, r = t.getContext("2d");
                try {
                    try {
                        o = r.getImageData(e, i, n, a)
                    } catch (s) {
                        throw new Error("unable to access local image data: " + s)
                    }
                } catch (s) {
                    throw new Error("unable to access image data: " + s)
                }
                return o
            }

            function e(e, n, a, o, r, s) {
                if (!(isNaN(s) || 1 > s)) {
                    s |= 0;
                    var l = t(e, n, a, o, r);
                    l = i(l, n, a, o, r, s), e.getContext("2d").putImageData(l, n, a)
                }
            }

            function i(t, e, i, r, s, l) {
                var h, u, c, d, p, m, f, g, v, _, y, w, b, k, E, C, x, S, I, T, P, O, M, L, D = t.data,
                    A = l + l + 1,
                    F = r - 1,
                    R = s - 1,
                    z = l + 1,
                    H = z * (z + 1) / 2,
                    U = new n,
                    B = U;
                for (c = 1; A > c; c++)
                    if (B = B.next = new n, c == z) var N = B;
                B.next = U;
                var W = null,
                    q = null;
                f = m = 0;
                var j = a[l],
                    G = o[l];
                for (u = 0; s > u; u++) {
                    for (C = x = S = I = g = v = _ = y = 0, w = z * (T = D[m]), b = z * (P = D[m + 1]), k = z * (O = D[m + 2]), E = z * (M = D[m + 3]), g += H * T, v += H * P, _ += H * O, y += H * M, B = U, c = 0; z > c; c++) B.r = T, B.g = P, B.b = O, B.a = M, B = B.next;
                    for (c = 1; z > c; c++) d = m + ((c > F ? F : c) << 2), g += (B.r = T = D[d]) * (L = z - c), v += (B.g = P = D[d + 1]) * L, _ += (B.b = O = D[d + 2]) * L, y += (B.a = M = D[d + 3]) * L, C += T, x += P, S += O, I += M, B = B.next;
                    for (W = U, q = N, h = 0; r > h; h++) D[m + 3] = M = y * j >> G, 0 != M ? (M = 255 / M, D[m] = (g * j >> G) * M, D[m + 1] = (v * j >> G) * M, D[m + 2] = (_ * j >> G) * M) : D[m] = D[m + 1] = D[m + 2] = 0, g -= w, v -= b, _ -= k, y -= E, w -= W.r, b -= W.g, k -= W.b, E -= W.a, d = f + ((d = h + l + 1) < F ? d : F) << 2, C += W.r = D[d], x += W.g = D[d + 1], S += W.b = D[d + 2], I += W.a = D[d + 3], g += C, v += x, _ += S, y += I, W = W.next, w += T = q.r, b += P = q.g, k += O = q.b, E += M = q.a, C -= T, x -= P, S -= O, I -= M, q = q.next, m += 4;
                    f += r
                }
                for (h = 0; r > h; h++) {
                    for (x = S = I = C = v = _ = y = g = 0, m = h << 2, w = z * (T = D[m]), b = z * (P = D[m + 1]), k = z * (O = D[m + 2]), E = z * (M = D[m + 3]), g += H * T, v += H * P, _ += H * O, y += H * M, B = U, c = 0; z > c; c++) B.r = T, B.g = P, B.b = O, B.a = M, B = B.next;
                    for (p = r, c = 1; l >= c; c++) m = p + h << 2, g += (B.r = T = D[m]) * (L = z - c), v += (B.g = P = D[m + 1]) * L, _ += (B.b = O = D[m + 2]) * L, y += (B.a = M = D[m + 3]) * L, C += T, x += P, S += O, I += M, B = B.next, R > c && (p += r);
                    for (m = h, W = U, q = N, u = 0; s > u; u++) d = m << 2, D[d + 3] = M = y * j >> G, M > 0 ? (M = 255 / M, D[d] = (g * j >> G) * M, D[d + 1] = (v * j >> G) * M, D[d + 2] = (_ * j >> G) * M) : D[d] = D[d + 1] = D[d + 2] = 0, g -= w, v -= b, _ -= k, y -= E, w -= W.r, b -= W.g, k -= W.b, E -= W.a, d = h + ((d = u + z) < R ? d : R) * r << 2, g += C += W.r = D[d], v += x += W.g = D[d + 1], _ += S += W.b = D[d + 2], y += I += W.a = D[d + 3], W = W.next, w += T = q.r, b += P = q.g, k += O = q.b, E += M = q.a, C -= T, x -= P, S -= O, I -= M, q = q.next, m += r
                }
                return t
            }

            function n() {
                this.r = 0, this.g = 0, this.b = 0, this.a = 0, this.next = null
            }
            var a = [512, 512, 456, 512, 328, 456, 335, 512, 405, 328, 271, 456, 388, 335, 292, 512, 454, 405, 364, 328, 298, 271, 496, 456, 420, 388, 360, 335, 312, 292, 273, 512, 482, 454, 428, 405, 383, 364, 345, 328, 312, 298, 284, 271, 259, 496, 475, 456, 437, 420, 404, 388, 374, 360, 347, 335, 323, 312, 302, 292, 282, 273, 265, 512, 497, 482, 468, 454, 441, 428, 417, 405, 394, 383, 373, 364, 354, 345, 337, 328, 320, 312, 305, 298, 291, 284, 278, 271, 265, 259, 507, 496, 485, 475, 465, 456, 446, 437, 428, 420, 412, 404, 396, 388, 381, 374, 367, 360, 354, 347, 341, 335, 329, 323, 318, 312, 307, 302, 297, 292, 287, 282, 278, 273, 269, 265, 261, 512, 505, 497, 489, 482, 475, 468, 461, 454, 447, 441, 435, 428, 422, 417, 411, 405, 399, 394, 389, 383, 378, 373, 368, 364, 359, 354, 350, 345, 341, 337, 332, 328, 324, 320, 316, 312, 309, 305, 301, 298, 294, 291, 287, 284, 281, 278, 274, 271, 268, 265, 262, 259, 257, 507, 501, 496, 491, 485, 480, 475, 470, 465, 460, 456, 451, 446, 442, 437, 433, 428, 424, 420, 416, 412, 408, 404, 400, 396, 392, 388, 385, 381, 377, 374, 370, 367, 363, 360, 357, 354, 350, 347, 344, 341, 338, 335, 332, 329, 326, 323, 320, 318, 315, 312, 310, 307, 304, 302, 299, 297, 294, 292, 289, 287, 285, 282, 280, 278, 275, 273, 271, 269, 267, 265, 263, 261, 259],
                o = [9, 11, 12, 13, 13, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24];
            return e
        }(),
        _createClass = function() {
            function t(t, e) {
                for (var i = 0; i < e.length; i++) {
                    var n = e[i];
                    n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
                }
            }
            return function(e, i, n) {
                return i && t(e.prototype, i), n && t(e, n), e
            }
        }();
    ! function() {
        function t(t, e) {
            e = e || {
                    bubbles: !1,
                    cancelable: !1,
                    detail: void 0
                };
            var i = document.createEvent("CustomEvent");
            return i.initCustomEvent(t, e.bubbles, e.cancelable, e.detail), i
        }
        t.prototype = window.CustomEvent.prototype, window.CustomEvent = t
    }(), HTMLCanvasElement.prototype.toBlob || Object.defineProperty(HTMLCanvasElement.prototype, "toBlob", {
        value: function(t, e, i) {
            for (var n = atob(this.toDataURL(e, i).split(",")[1]), a = n.length, o = new Uint8Array(a), r = 0; a > r; r++) o[r] = n.charCodeAt(r);
            t(new Blob([o], {
                type: e || "image/png"
            }))
        }
    });
    var Key = {
            ESC: 27,
            RETURN: 13
        },
        Events = {
            DOWN: ["touchstart", "pointerdown", "mousedown"],
            MOVE: ["touchmove", "pointermove", "mousemove"],
            UP: ["touchend", "touchcancel", "pointerup", "mouseup"]
        },
        mimetypes = {
            jpeg: "image/jpeg",
            jpg: "image/jpeg",
            jpe: "image/jpeg",
            png: "image/png",
            gif: "image/gif",
            bmp: "image/bmp"
        },
        resizers = {
            n: function(t, e, i, n) {
                var a, o, r, s, l, h, u, c;
                return r = t.y + t.height, a = limit(e.y, 0, r), r - a < i.min.height && (a = r - i.min.height), l = n ? (r - a) / n : t.width, l < i.min.width && (l = i.min.width, a = r - l * n), u = .5 * (l - t.width), s = t.x - u, o = t.x + t.width + u, (0 > s || o > i.width) && (c = Math.min(t.x, i.width - (t.x + t.width)), s = t.x - c, o = t.x + t.width + c, l = o - s, h = l * n, a = r - h), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            },
            s: function(t, e, i, n) {
                var a, o, r, s, l, h, u, c;
                return a = t.y, r = limit(e.y, a, i.height), r - a < i.min.height && (r = a + i.min.height), l = n ? (r - a) / n : t.width, l < i.min.width && (l = i.min.width, r = a + l * n), u = .5 * (l - t.width), s = t.x - u, o = t.x + t.width + u, (0 > s || o > i.width) && (c = Math.min(t.x, i.width - (t.x + t.width)), s = t.x - c, o = t.x + t.width + c, l = o - s, h = l * n, r = a + h), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            },
            e: function(t, e, i, n) {
                var a, o, r, s, l, h, u, c;
                return s = t.x, o = limit(e.x, s, i.width), o - s < i.min.width && (o = s + i.min.width), h = n ? (o - s) * n : t.height, h < i.min.height && (h = i.min.height, o = s + h / n), u = .5 * (h - t.height), a = t.y - u, r = t.y + t.height + u, (0 > a || r > i.height) && (c = Math.min(t.y, i.height - (t.y + t.height)), a = t.y - c, r = t.y + t.height + c, h = r - a, l = h / n, o = s + l), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            },
            w: function t(e, i, n, a) {
                var o, r, s, l, t, h, u, c;
                return r = e.x + e.width, l = limit(i.x, 0, r), r - l < n.min.width && (l = r - n.min.width), h = a ? (r - l) * a : e.height, h < n.min.height && (h = n.min.height, l = r - h / a), u = .5 * (h - e.height), o = e.y - u, s = e.y + e.height + u, (0 > o || s > n.height) && (c = Math.min(e.y, n.height - (e.y + e.height)), o = e.y - c, s = e.y + e.height + c, h = s - o, t = h / a, l = r - t), {
                    x: l,
                    y: o,
                    width: r - l,
                    height: s - o
                }
            },
            ne: function(t, e, i, n) {
                var a, o, r, s, l, h, u;
                return s = t.x, r = t.y + t.height, o = limit(e.x, s, i.width), o - s < i.min.width && (o = s + i.min.width), h = n ? (o - s) * n : limit(r - e.y, i.min.height, r), h < i.min.height && (h = i.min.height, o = s + h / n), a = t.y - (h - t.height), (0 > a || r > i.height) && (u = Math.min(t.y, i.height - (t.y + t.height)), a = t.y - u, h = r - a, l = h / n, o = s + l), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            },
            se: function(t, e, i, n) {
                var a, o, r, s, l, h, u;
                return s = t.x, a = t.y, o = limit(e.x, s, i.width), o - s < i.min.width && (o = s + i.min.width), h = n ? (o - s) * n : limit(e.y - t.y, i.min.height, i.height - a), h < i.min.height && (h = i.min.height, o = s + h / n), r = t.y + t.height + (h - t.height), (0 > a || r > i.height) && (u = Math.min(t.y, i.height - (t.y + t.height)), r = t.y + t.height + u, h = r - a, l = h / n, o = s + l), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            },
            sw: function(t, e, i, n) {
                var a, o, r, s, l, h, u;
                return o = t.x + t.width, a = t.y, s = limit(e.x, 0, o), o - s < i.min.width && (s = o - i.min.width), h = n ? (o - s) * n : limit(e.y - t.y, i.min.height, i.height - a), h < i.min.height && (h = i.min.height, s = o - h / n), r = t.y + t.height + (h - t.height), (0 > a || r > i.height) && (u = Math.min(t.y, i.height - (t.y + t.height)), r = t.y + t.height + u, h = r - a, l = h / n, s = o - l), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            },
            nw: function(t, e, i, n) {
                var a, o, r, s, l, h, u;
                return o = t.x + t.width, r = t.y + t.height, s = limit(e.x, 0, o), o - s < i.min.width && (s = o - i.min.width), h = n ? (o - s) * n : limit(r - e.y, i.min.height, r), h < i.min.height && (h = i.min.height, s = o - h / n), a = t.y - (h - t.height), (0 > a || r > i.height) && (u = Math.min(t.y, i.height - (t.y + t.height)), a = t.y - u, h = r - a, l = h / n, s = o - l), {
                    x: s,
                    y: a,
                    width: o - s,
                    height: r - a
                }
            }
        },
        CropArea = function() {
            function t() {
                var e = arguments.length <= 0 || void 0 === arguments[0] ? document.createElement("div") : arguments[0];
                _classCallCheck(this, t), this._element = e, this._interaction = null, this._minWidth = 0, this._minHeight = 0, this._ratio = null, this._rect = {
                    x: 0,
                    y: 0,
                    width: 0,
                    height: 0
                }, this._space = {
                    width: 0,
                    height: 0
                }, this._rectChanged = !1, this._init()
            }
            return _createClass(t, [{
                key: "_init",
                value: function() {
                    this._element.className = "slim-crop-area";
                    var t = create("div", "grid");
                    this._element.appendChild(t);
                    for (var e in resizers)
                        if (resizers.hasOwnProperty(e)) {
                            var i = create("button", e);
                            this._element.appendChild(i)
                        }
                    var n = create("button", "c");
                    this._element.appendChild(n), addEvents(document, Events.DOWN, this)
                }
            }, {
                key: "reset",
                value: function() {
                    this._interaction = null, this._rect = {
                        x: 0,
                        y: 0,
                        width: 0,
                        height: 0
                    }, this._rectChanged = !0, this._redraw(), this._element.dispatchEvent(new CustomEvent("change"))
                }
            }, {
                key: "rescale",
                value: function(t) {
                    1 !== t && (this._interaction = null, this._rectChanged = !0, this._rect.x *= t, this._rect.y *= t, this._rect.width *= t, this._rect.height *= t, this._redraw(), this._element.dispatchEvent(new CustomEvent("change")))
                }
            }, {
                key: "limit",
                value: function(t, e) {
                    this._space = {
                        width: t,
                        height: e
                    }
                }
            }, {
                key: "resize",
                value: function(t, e, i, n) {
                    this._interaction = null, this._rect = {
                        x: t,
                        y: e,
                        width: limit(i, 0, this._space.width),
                        height: limit(n, 0, this._space.height)
                    }, this._rectChanged = !0, this._redraw(), this._element.dispatchEvent(new CustomEvent("change"))
                }
            }, {
                key: "handleEvent",
                value: function(t) {
                    switch (t.type) {
                        case "touchstart":
                        case "pointerdown":
                        case "mousedown":
                            this._onStartDrag(t);
                            break;
                        case "touchmove":
                        case "pointermove":
                        case "mousemove":
                            this._onDrag(t);
                            break;
                        case "touchend":
                        case "touchcancel":
                        case "pointerup":
                        case "mouseup":
                            this._onStopDrag(t)
                    }
                }
            }, {
                key: "_onStartDrag",
                value: function(t) {
                    this._element.contains(t.target) && (t.preventDefault(), addEvents(document, Events.MOVE, this), addEvents(document, Events.UP, this), this._interaction = {
                        type: t.target.className,
                        offset: getEventOffsetLocal(t, this._element)
                    }, this._element.setAttribute("data-dragging", "true"), this._redraw())
                }
            }, {
                key: "_onDrag",
                value: function(t) {
                    t.preventDefault();
                    var e = getEventOffsetLocal(t, this._element.parentNode),
                        i = this._interaction.type;
                    "c" === i ? (this._rect.x = limit(e.x - this._interaction.offset.x, 0, this._space.width - this._rect.width), this._rect.y = limit(e.y - this._interaction.offset.y, 0, this._space.height - this._rect.height)) : resizers[i] && (this._rect = resizers[i](this._rect, e, {
                        x: 0,
                        y: 0,
                        width: this._space.width,
                        height: this._space.height,
                        min: {
                            width: this._minWidth,
                            height: this._minHeight
                        }
                    }, this._ratio)), this._rectChanged = !0, this._element.dispatchEvent(new CustomEvent("input"))
                }
            }, {
                key: "_onStopDrag",
                value: function(t) {
                    t.preventDefault(), removeEvents(document, Events.MOVE, this), removeEvents(document, Events.UP, this), this._interaction = null, this._element.setAttribute("data-dragging", "false"), this._element.dispatchEvent(new CustomEvent("change"))
                }
            }, {
                key: "_redraw",
                value: function() {
                    var t = this;
                    this._rectChanged && (this._element.style.cssText = "\n                left:" + this._rect.x + "px;\n                top:" + this._rect.y + "px;\n                width:" + this._rect.width + "px;\n                height:" + this._rect.height + "px;\n            ", this._rectChanged = !1), this._interaction && requestAnimationFrame(function() {
                        return t._redraw()
                    })
                }
            }, {
                key: "destroy",
                value: function() {
                    this._interaction = !1, this._rectChanged = !1, removeEvents(document, Events.DOWN, this), removeEvents(document, Events.MOVE, this), removeEvents(document, Events.UP, this), removeElement(this._element)
                }
            }, {
                key: "element",
                get: function() {
                    return this._element
                }
            }, {
                key: "area",
                get: function() {
                    return this._rect
                }
            }, {
                key: "dirty",
                get: function() {
                    return 0 !== this._rect.x || 0 !== this._rect.y || 0 !== this._rect.width || 0 !== this._rect.height
                }
            }, {
                key: "minWidth",
                set: function(t) {
                    this._minWidth = t
                }
            }, {
                key: "minHeight",
                set: function(t) {
                    this._minHeight = t
                }
            }, {
                key: "ratio",
                set: function(t) {
                    this._ratio = t
                }
            }]), t
        }(),
        ImageEditorButtons = ["cancel", "confirm"],
        ImageCropperEvents = ["input", "change"],
        ImageEditor = function() {
            function t() {
                var e = arguments.length <= 0 || void 0 === arguments[0] ? document.createElement("div") : arguments[0],
                    i = arguments.length <= 1 || void 0 === arguments[1] ? {} : arguments[1];
                _classCallCheck(this, t), this._element = e, this._options = mergeOptions(t.options(), i), this._ratio = null, this._output = null, this._input = null, this._preview = null, this._previewBlurred = null, this._blurredPreview = !1, this._cropper = null, this._previewWrapper = null, this._currentWindowSize = {}, this._btnGroup = null, this._init()
            }
            return _createClass(t, [{
                key: "_init",
                value: function() {
                    var t = this;
                    this._element.className = "slim-image-editor", this._wrapper = create("div", "slim-wrapper"), this._stage = create("div", "slim-stage"), this._wrapper.appendChild(this._stage), this._cropper = new CropArea, ImageCropperEvents.forEach(function(e) {
                        t._cropper.element.addEventListener(e, t)
                    }), this._stage.appendChild(this._cropper.element), this._previewWrapper = create("div", "slim-image-editor-preview slim-crop-preview"), this._previewBlurred = create("canvas", "slim-crop-blur"), this._previewWrapper.appendChild(this._previewBlurred), this._wrapper.appendChild(this._previewWrapper), this._preview = create("img", "slim-crop"), this._previewWrapper.appendChild(this._preview), this._btnGroup = create("div", "slim-btn-group"), ImageEditorButtons.forEach(function(e) {
                        var i = capitalizeFirstLetter(e),
                            n = t._options["button" + i + "Label"],
                            a = t._options["button" + i + "Title"],
                            o = t._options["button" + i + "ClassName"],
                            r = create("button", "slim-image-editor-btn slim-btn-" + e + (o ? " " + o : ""));
                        r.innerHTML = n, r.title = a || n, r.type = "button", r.setAttribute("data-action", e), r.addEventListener("click", t), t._btnGroup.appendChild(r)
                    }), this._element.appendChild(this._wrapper), this._element.appendChild(this._btnGroup)
                }
            }, {
                key: "handleEvent",
                value: function(t) {
                    switch (t.type) {
                        case "click":
                            this._onClick(t);
                            break;
                        case "change":
                            this._onGridChange(t);
                            break;
                        case "input":
                            this._onGridInput(t);
                            break;
                        case "keydown":
                            this._onKeyDown(t);
                            break;
                        case "resize":
                            this._onResize(t)
                    }
                }
            }, {
                key: "_onKeyDown",
                value: function(t) {
                    switch (t.keyCode) {
                        case Key.RETURN:
                            this._confirm();
                            break;
                        case Key.ESC:
                            this._cancel()
                    }
                }
            }, {
                key: "_onClick",
                value: function(t) {
                    t.target.classList.contains("slim-btn-cancel") && this._cancel(), t.target.classList.contains("slim-btn-confirm") && this._confirm()
                }
            }, {
                key: "_onResize",
                value: function() {
                    var t = this;
                    this._currentWindowSize = {
                        width: window.innerWidth,
                        height: window.innerHeight
                    }, clearTimeout(this._resizeTimer), this._resizeTimer = setTimeout(function() {
                        requestAnimationFrame(function() {
                            t._reflow()
                        })
                    }, 10)
                }
            }, {
                key: "_reflow",
                value: function() {
                    var t = this._wrapper.offsetWidth;
                    this._redraw(), this._cropper.rescale(this._wrapper.offsetWidth / t)
                }
            }, {
                key: "_onGridInput",
                value: function() {
                    this._maskOriginal()
                }
            }, {
                key: "_onGridChange",
                value: function() {
                    this._maskOriginal()
                }
            }, {
                key: "_cancel",
                value: function() {
                    this._element.dispatchEvent(new CustomEvent("cancel"))
                }
            }, {
                key: "_confirm",
                value: function() {
                    this._element.dispatchEvent(new CustomEvent("confirm", {
                        detail: {
                            crop: scaleRect(this._cropper.area, this._input.width / this._preview.width)
                        }
                    }))
                }
            }, {
                key: "open",
                value: function(t, e, i) {
                    var n = this;
                    if (this._input && this._input.getAttribute("data-file") == t.getAttribute("data-file")) return void i();
                    this._input = t, this._preview.onload = function() {
                        n._redraw(), requestAnimationFrame(function() {
                            var t = n.ratio;
                            n._cropper.minWidth = n._options.minSize.width / n.scalar, n._cropper.minHeight = n._options.minSize.height / n.scalar, n._cropper.ratio = t;
                            var e, a, o = n._preview.width,
                                r = n._preview.height,
                                s = o,
                                l = t ? s * t : r;
                            l > r && (l = r, s = l / t), a = .5 * (o - s), e = .5 * (r - l);
                            var h = n._stage.getBoundingClientRect();
                            n._cropper.limit(h.width, h.height), n._cropper.resize(a, e, s, l), i(), n._element.style.opacity = ""
                        })
                    };
                    var a = Math.min(this._element.offsetWidth / this._input.width, this._element.offsetHeight / this._input.height);
                    this._preview.src = cloneCanvasScaled(this._input, a).toDataURL(), this._element.style.opacity = "0", this._ratio = e
                }
            }, {
                key: "_maskOriginal",
                value: function() {
                    var t = this,
                        e = this._cropper.area;
                    requestAnimationFrame(function() {
                        t._preview.style.clip = "rect(" + e.y + "px," + (e.x + e.width) + "px," + (e.y + e.height) + "px," + e.x + "px)"
                    })
                }
            }, {
                key: "_reset",
                value: function() {
                    this._preview.src = "", clearCanvas(this._previewBlurred), this._cropper.reset()
                }
            }, {
                key: "_redraw",
                value: function() {
                    var t = this._input.height / this._input.width,
                        e = this._btnGroup.offsetHeight,
                        i = this._element.clientWidth,
                        n = this._element.clientHeight - e,
                        a = i,
                        o = a * t;
                    o > n && (o = n, a = o / t), a = Math.round(a), o = Math.round(o), this._previewBlurred.style.cssText = this._wrapper.style.cssText = "\n            width:" + a + "px;\n            height:" + o + "px;\n        ", this._preview.width = a, this._preview.height = o, this._blurredPreview || (this._previewBlurred.width = 300, this._previewBlurred.height = this._previewBlurred.width * t, copyCanvas(this._input, this._previewBlurred), blurCanvas(this._previewBlurred, 3), this._blurredPreview = !0)
                }
            }, {
                key: "show",
                value: function() {
                    var t = arguments.length <= 0 || void 0 === arguments[0] ? function() {} : arguments[0];
                    this._currentWindowSize.width === window.innerWidth && this._currentWindowSize.height === window.innerHeight || this._reflow(), document.addEventListener("keydown", this), window.addEventListener("resize", this), snabbt(this._previewWrapper, {
                        fromPosition: [0, 0, 0],
                        position: [0, 0, 0],
                        fromOpacity: 0,
                        opacity: .9999,
                        fromScale: [.98, .98],
                        scale: [1, 1],
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .85,
                        delay: 450,
                        complete: function() {
                            resetTransforms(this)
                        }
                    }), this._cropper.dirty ? snabbt(this._stage, {
                        fromPosition: [0, 0, 0],
                        position: [0, 0, 0],
                        fromOpacity: 0,
                        opacity: 1,
                        duration: 250,
                        delay: 550,
                        complete: function() {
                            resetTransforms(this), t()
                        }
                    }) : snabbt(this._stage, {
                        fromPosition: [0, 0, 0],
                        position: [0, 0, 0],
                        fromOpacity: 0,
                        opacity: 1,
                        duration: 250,
                        delay: 1e3,
                        complete: function() {
                            resetTransforms(this)
                        }
                    }), snabbt(this._btnGroup.childNodes, {
                        fromScale: [.9, .9],
                        scale: [1, 1],
                        fromOpacity: 0,
                        opacity: 1,
                        delay: function(t) {
                            return 1e3 + 100 * t
                        },
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .85,
                        complete: function() {
                            resetTransforms(this)
                        }
                    })
                }
            }, {
                key: "hide",
                value: function() {
                    var t = arguments.length <= 0 || void 0 === arguments[0] ? function() {} : arguments[0];
                    document.removeEventListener("keydown", this), window.removeEventListener("resize", this), snabbt(this._btnGroup.childNodes, {
                        fromOpacity: 1,
                        opacity: 0,
                        duration: 350
                    }), snabbt([this._stage, this._previewWrapper], {
                        fromPosition: [0, 0, 0],
                        position: [0, -250, 0],
                        fromOpacity: 1,
                        opacity: 0,
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .75,
                        delay: 250,
                        allDone: function() {
                            t()
                        }
                    })
                }
            }, {
                key: "destroy",
                value: function() {
                    var t = this;
                    nodeListToArray(this._btnGroup.children).forEach(function(e) {
                        e.removeEventListener("click", t)
                    }), ImageCropperEvents.forEach(function(e) {
                        t._cropper.element.removeEventListener(e, t)
                    }), this._cropper.destroy(), removeElement(this._element)
                }
            }, {
                key: "element",
                get: function() {
                    return this._element
                }
            }, {
                key: "ratio",
                get: function() {
                    return "input" === this._ratio ? this._input.height / this._input.width : this._ratio
                }
            }, {
                key: "offset",
                get: function() {
                    return this._element.getBoundingClientRect()
                }
            }, {
                key: "original",
                get: function() {
                    return this._input
                }
            }, {
                key: "scalar",
                get: function() {
                    return this._input.width / this._preview.width
                }
            }], [{
                key: "options",
                value: function() {
                    return {
                        buttonCancelClassName: null,
                        buttonConfirmClassName: null,
                        buttonCancelLabel: "Cancel",
                        buttonConfirmLabel: "Confirm",
                        buttonCancelTitle: null,
                        buttonConfirmTitle: null,
                        minSize: {
                            width: 0,
                            height: 0
                        }
                    }
                }
            }]), t
        }(),
        DragDropEvents = ["dragover", "dragleave", "drop"],
        FileHopper = function() {
            function t() {
                var e = arguments.length <= 0 || void 0 === arguments[0] ? document.createElement("div") : arguments[0];
                _classCallCheck(this, t), this._element = e, this._accept = [], this._dragPath = null, this._init()
            }
            return _createClass(t, [{
                key: "areValidFiles",
                value: function(t) {
                    return this._accept.length && t ? -1 != this._accept.indexOf(t[0].type) : !0
                }
            }, {
                key: "reset",
                value: function() {
                    this._element.files = null
                }
            }, {
                key: "_init",
                value: function() {
                    var t = this;
                    this._element.className = "slim-file-hopper", DragDropEvents.forEach(function(e) {
                        t._element.addEventListener(e, t)
                    })
                }
            }, {
                key: "handleEvent",
                value: function(t) {
                    switch (t.type) {
                        case "dragover":
                            this._onDragOver(t);
                            break;
                        case "dragleave":
                            this._onDragLeave(t);
                            break;
                        case "drop":
                            this._onDrop(t)
                    }
                }
            }, {
                key: "_onDrop",
                value: function(t) {
                    return t.preventDefault(), this.areValidFiles(t.dataTransfer.files) ? (this._element.files = t.dataTransfer.files, this._element.dispatchEvent(new CustomEvent("file-drop", {
                        detail: getOffsetByEvent(t)
                    })), this._element.dispatchEvent(new CustomEvent("change")), void(this._dragPath = null)) : (this._element.dispatchEvent(new CustomEvent("file-invalid-drop")), void(this._dragPath = null))
                }
            }, {
                key: "_onDragOver",
                value: function(t) {
                    return t.preventDefault(), t.dataTransfer.dropEffect = "copy", this.areValidFiles(t.dataTransfer.items) ? (this._dragPath || (this._dragPath = []), this._dragPath.push(getOffsetByEvent(t)), void this._element.dispatchEvent(new CustomEvent("file-over", {
                        detail: {
                            x: last(this._dragPath).x,
                            y: last(this._dragPath).y
                        }
                    }))) : (t.dataTransfer.dropEffect = "none", void this._element.dispatchEvent(new CustomEvent("file-invalid")))
                }
            }, {
                key: "_onDragLeave",
                value: function(t) {
                    this._element.dispatchEvent(new CustomEvent("file-out", {
                        detail: getOffsetByEvent(t)
                    })), this._dragPath = null
                }
            }, {
                key: "destroy",
                value: function() {
                    var t = this;
                    DragDropEvents.forEach(function(e) {
                        t._element.removeEventListener(e, t)
                    }), removeElement(this._element)
                }
            }, {
                key: "element",
                get: function() {
                    return this._element
                }
            }, {
                key: "dragPath",
                get: function() {
                    return this._dragPath
                }
            }, {
                key: "enabled",
                get: function() {
                    return "" === this._element.style.display
                },
                set: function(t) {
                    this._element.style.display = t ? "" : "none"
                }
            }, {
                key: "accept",
                set: function(t) {
                    this._accept = t
                },
                get: function() {
                    return this._accept
                }
            }]), t
        }(),
        Popover = function() {
            function t() {
                _classCallCheck(this, t), this._element = null, this._inner = null, this._init()
            }
            return _createClass(t, [{
                key: "_init",
                value: function() {
                    this._element = create("div", "slim-popover"), this._element.setAttribute("data-state", "off"), document.body.appendChild(this._element)
                }
            }, {
                key: "show",
                value: function() {
                    var t = this,
                        e = arguments.length <= 0 || void 0 === arguments[0] ? function() {} : arguments[0];
                    this._element.setAttribute("data-state", "on"), snabbt(this._element, {
                        fromOpacity: 0,
                        opacity: 1,
                        duration: 350,
                        complete: function() {
                            resetTransforms(t._element), e()
                        }
                    })
                }
            }, {
                key: "hide",
                value: function() {
                    var t = this,
                        e = arguments.length <= 0 || void 0 === arguments[0] ? function() {} : arguments[0];
                    snabbt(this._element, {
                        fromOpacity: 1,
                        opacity: 0,
                        duration: 500,
                        complete: function() {
                            resetTransforms(t._element), t._element.setAttribute("data-state", "off"), e()
                        }
                    })
                }
            }, {
                key: "inner",
                set: function(t) {
                    this._inner = t, this._element.firstChild && this._element.removeChild(this._element.firstChild), this._element.appendChild(this._inner)
                }
            }]), t
        }(),
        HopperEvents = ["file-invalid-drop", "file-invalid", "file-drop", "file-over", "file-out", "click"],
        SlimPopover = null,
        SlimButtons = ["remove", "edit", "download", "upload"],
        SlimType = {
            IMG: "img",
            INPUT: "input"
        },
        SlimLoaderHTML = '<div class="slim-loader">\n                        <svg>\n                            <path class="slim-loader-background" fill="none" stroke-width="3" />\n                            <path class="slim-loader-foreground" fill="none" stroke-width="3" />\n                        </svg>\n                    </div>',
        SlimUploadStatusHTML = '<div class="slim-upload-status"></div>',
        Slim = function() {
            function t(e) {
                var i = arguments.length <= 1 || void 0 === arguments[1] ? {} : arguments[1];
                _classCallCheck(this, t), SlimPopover || (SlimPopover = new Popover), this._options = mergeOptions(t.options(), i), this._originalElement = e, isWrapper(e) ? this._element = e : (this._element = wrap(e), this._element.className = e.className, e.className = "", this._element.setAttribute("data-ratio", this._options.ratio)), this._element.classList.add("slim"), this._element.setAttribute("data-state", "init"), this._autoAcceptCommonMimeTypes = !1, this._autoInput = !1, this._autoOutput = !1, this._input = null, this._output = null, this._ratio = null, this._isRequired = !1, this._imageHopper = null, this._imageEditor = null, this._progressEnabled = !0, this._data = {}, this._resetData(), this._state = [], this._drip = null, t.supported ? this._init() : this._fallback()
            }
            return _createClass(t, [{
                key: "isAttachedTo",
                value: function(t) {
                    return this._element === t || this._originalElement === t
                }
            }, {
                key: "load",
                value: function(t, e) {
                    this._load(t, e)
                }
            }, {
                key: "upload",
                value: function(t) {
                    this._doUpload(t)
                }
            }, {
                key: "download",
                value: function() {
                    this._doDownload()
                }
            }, {
                key: "reset",
                value: function() {
                    this._doReset()
                }
            }, {
                key: "destroy",
                value: function() {
                    this._doDestroy()
                }
            }, {
                key: "_getInputElement",
                value: function() {
                    return this._element.querySelector("input[type=file]") || this._element.querySelector("img")
                }
            }, {
                key: "_getSlimType",
                value: function() {
                    return "INPUT" === this._input.nodeName ? SlimType.INPUT : SlimType.IMG
                }
            }, {
                key: "_isFixedRatio",
                value: function() {
                    return -1 !== this._options.ratio.indexOf(":")
                }
            }, {
                key: "_toggleButton",
                value: function(t, e) {
                    toggleDisplayBySelector('.slim-btn[data-action="' + t + '"]', e, this._element)
                }
            }, {
                key: "_clearState",
                value: function() {
                    this._state = [], this._updateState()
                }
            }, {
                key: "_removeState",
                value: function(t) {
                    this._state = this._state.filter(function(e) {
                        return e !== t
                    }), this._updateState()
                }
            }, {
                key: "_addState",
                value: function(t) {
                    inArray(t, this._state) || (this._state.push(t), this._updateState())
                }
            }, {
                key: "_updateState",
                value: function() {
                    this._element.setAttribute("data-state", this._state.join(","))
                }
            }, {
                key: "_resetData",
                value: function() {
                    this._data = {
                        input: {
                            image: null,
                            name: null,
                            type: null,
                            width: 0,
                            height: 0
                        },
                        output: {
                            image: null,
                            width: 0,
                            height: 0
                        },
                        actions: {
                            crop: null,
                            size: null
                        }
                    }
                }
            }, {
                key: "_init",
                value: function() {
                    var t = this;
                    this._addState("empty"), this._input = this._getInputElement(), this._input || (this._input = create("input"), this._input.type = "file", this._element.appendChild(this._input), this._autoInput = !0), this._isRequired = this._input.required === !0, this._output = this._element.querySelector("input[type=hidden]"), this._output || (this._output = create("input"), this._output.type = "hidden", this._output.name = this._input.name || this._options.defaultInputName, this._options.service || (this._element.appendChild(this._output), this._autoOutput = !0)), this._input.removeAttribute("name");
                    var e = create("div", "slim-area");
                    if (this._getSlimType() == SlimType.INPUT) {
                        var i = void 0;
                        this._input.hasAttribute("accept") ? i = this._input.accept.split(",").filter(function(t) {
                            return t.length > 0
                        }) : (i = getCommonMimeTypes(), this._input.setAttribute("accept", i.join(",")), this._autoAcceptCommonMimeTypes = !0), this._imageHopper = new FileHopper, this._imageHopper.accept = i, this._element.appendChild(this._imageHopper.element), HopperEvents.forEach(function(e) {
                            t._imageHopper.element.addEventListener(e, t)
                        }), e.innerHTML = "\n                " + SlimLoaderHTML + "\n                " + SlimUploadStatusHTML + '\n                <div class="slim-drip"><span><span></span></span></div>\n                <div class="slim-status"><div class="slim-label">' + (this._options.label || "") + '</div></div>\n                <div class="slim-result"><img class="in" style="opacity:0"><img style="opacity:0"></div>\n            ', this._input.addEventListener("change", this)
                    } else e.innerHTML = "\n                " + SlimLoaderHTML + "\n                " + SlimUploadStatusHTML + '\n                <div class="slim-result"><img class="in" style="opacity:0" src="' + this._input.src + '"><img></div>\n            ';
                    if (this._element.appendChild(e), this._btnGroup = create("div", "slim-btn-group"), this._btnGroup.style.display = "none", this._element.appendChild(this._btnGroup), SlimButtons.filter(function(e) {
                            return t._isButtonAllowed(e)
                        }).forEach(function(e) {
                            var i = capitalizeFirstLetter(e),
                                n = t._options["button" + i + "Label"],
                                a = t._options["button" + i + "Title"] || n,
                                o = t._options["button" + i + "ClassName"],
                                r = create("button", "slim-btn slim-btn-" + e + (o ? " " + o : ""));
                            r.innerHTML = n, r.title = a, r.type = "button", r.addEventListener("click", t), r.setAttribute("data-action", e), t._btnGroup.appendChild(r)
                        }), this._isFixedRatio()) {
                        var n = intSplit(this._options.ratio, ":");
                        this._ratio = n[1] / n[0], this._scaleDropArea(this._ratio)
                    }
                    this._updateProgress(.5), this._preload()
                }
            }, {
                key: "_updateProgress",
                value: function(t) {
                    if (this._progressEnabled) {
                        var e = this._element.querySelector(".slim-loader"),
                            i = e.getBoundingClientRect(),
                            n = e.querySelectorAll("path"),
                            a = parseInt(n[0].getAttribute("stroke-width"), 10);
                        n[0].setAttribute("d", percentageArc(.5 * i.width, .5 * i.height, .5 * i.width - a, .9999)), n[1].setAttribute("d", percentageArc(.5 * i.width, .5 * i.height, .5 * i.width - a, t))
                    }
                }
            }, {
                key: "_startProgress",
                value: function() {
                    var t = this;
                    this._progressEnabled = !1;
                    var e = this._element.querySelector(".slim-loader"),
                        i = e.children[0];
                    this._stopProgressLoop(function() {
                        e.removeAttribute("style"), i.removeAttribute("style"), t._progressEnabled = !0, t._updateProgress(0), t._progressEnabled = !1, snabbt(i, {
                            fromOpacity: 0,
                            opacity: 1,
                            duration: 250,
                            complete: function() {
                                t._progressEnabled = !0
                            }
                        })
                    })
                }
            }, {
                key: "_stopProgress",
                value: function() {
                    var t = this,
                        e = this._element.querySelector(".slim-loader"),
                        i = e.children[0];
                    this._updateProgress(1), snabbt(i, {
                        fromOpacity: 1,
                        opacity: 0,
                        duration: 250,
                        complete: function() {
                            e.removeAttribute("style"), i.removeAttribute("style"), t._updateProgress(.5), t._progressEnabled = !1
                        }
                    })
                }
            }, {
                key: "_startProgressLoop",
                value: function() {
                    var t = this._element.querySelector(".slim-loader"),
                        e = t.children[0];
                    t.removeAttribute("style"), e.removeAttribute("style"), this._updateProgress(.5);
                    var i = 1e3;
                    snabbt(t, {
                        rotation: [0, 0, -(2 * Math.PI) * i],
                        easing: "linear",
                        duration: 1e3 * i
                    }), snabbt(e, {
                        fromOpacity: 0,
                        opacity: 1,
                        duration: 250
                    })
                }
            }, {
                key: "_stopProgressLoop",
                value: function(t) {
                    var e = this._element.querySelector(".slim-loader"),
                        i = e.children[0];
                    snabbt(i, {
                        fromOpacity: parseFloat(i.style.opacity),
                        opacity: 0,
                        duration: 250,
                        complete: function() {
                            snabbt(e, "stop"), t && t()
                        }
                    })
                }
            }, {
                key: "_isButtonAllowed",
                value: function(t) {
                    return "edit" === t ? this._options.edit : "download" === t ? this._options.download : "upload" === t ? this._options.service ? !this._options.push : !1 : !0
                }
            }, {
                key: "_fallback",
                value: function() {
                    this._removeState("init");
                    var t = create("div", "slim-area");
                    t.innerHTML = '\n            <div class="slim-status"><div class="slim-label">' + (this._options.label || "") + "</div></div>\n        ", this._element.appendChild(t), this._throwError(this._options.statusNoSupport)
                }
            }, {
                key: "_preload",
                value: function() {
                    this._getSlimType() != SlimType.INPUT && (this._toggleButton("remove", !1), this._load(this._input.src))
                }
            }, {
                key: "handleEvent",
                value: function(t) {
                    switch (t.type) {
                        case "click":
                            this._onClick(t);
                            break;
                        case "change":
                            this._onChange(t);
                            break;
                        case "cancel":
                            this._onCancel(t);
                            break;
                        case "confirm":
                            this._onConfirm(t);
                            break;
                        case "file-over":
                            this._onFileOver(t);
                            break;
                        case "file-out":
                            this._onFileOut(t);
                            break;
                        case "file-drop":
                            this._onDropFile(t);
                            break;
                        case "file-invalid":
                            this._onInvalidFile(t);
                            break;
                        case "file-invalid-drop":
                            this._onInvalidFileDrop(t)
                    }
                }
            }, {
                key: "_getIntro",
                value: function() {
                    return this._element.querySelector(".slim-result .in")
                }
            }, {
                key: "_getOutro",
                value: function() {
                    return this._element.querySelector(".slim-result .out")
                }
            }, {
                key: "_getInOut",
                value: function() {
                    return this._element.querySelectorAll(".slim-result img")
                }
            }, {
                key: "_getDrip",
                value: function() {
                    return this._drip || (this._drip = this._element.querySelector(".slim-drip > span")), this._drip
                }
            }, {
                key: "_throwError",
                value: function(t) {
                    this._addState("error"), this._element.querySelector(".slim-label").style.display = "none";
                    var e = this._element.querySelector(".slim-error");
                    e || (e = create("div", "slim-error"), this._element.querySelector(".slim-status").appendChild(e)), e.innerHTML = t
                }
            }, {
                key: "_removeError",
                value: function() {
                    this._removeState("error"), this._element.querySelector(".slim-label").style.display = "";
                    var t = this._element.querySelector(".slim-error");
                    t && t.parentNode.removeChild(t)
                }
            }, {
                key: "_openFileDialog",
                value: function() {
                    this._input.click()
                }
            }, {
                key: "_onClick",
                value: function(t) {
                    var e = t.target.classList,
                        i = t.target;
                    if (e.contains("slim-file-hopper")) return void this._openFileDialog();
                    switch (i.getAttribute("data-action")) {
                        case "remove":
                            this._doReset();
                            break;
                        case "edit":
                            this._doEdit();
                            break;
                        case "download":
                            this._doDownload();
                            break;
                        case "upload":
                            this._doUpload()
                    }
                }
            }, {
                key: "_onInvalidFileDrop",
                value: function() {
                    this._onInvalidFile(), this._removeState("file-over");
                    var t = this._getDrip();
                    snabbt(t.firstChild, {
                        fromScale: [.5, .5],
                        scale: [0, 0],
                        fromOpacity: .5,
                        opacity: 0,
                        duration: 150,
                        complete: function() {
                            resetTransforms(t.firstChild)
                        }
                    })
                }
            }, {
                key: "_onInvalidFile",
                value: function() {
                    var t = this._imageHopper.accept.map(getExtensionByMimeType),
                        e = this._options.statusFileType.replace("$0", t.join(", "));
                    this._throwError(e)
                }
            }, {
                key: "_onOverWeightFile",
                value: function() {
                    var t = this._options.statusFileSize.replace("$0", this._options.maxFileSize);
                    this._throwError(t)
                }
            }, {
                key: "_onFileOver",
                value: function(t) {
                    this._addState("file-over"), this._removeError();
                    var e = this._getDrip(),
                        i = snabbt.createMatrix();
                    i.translate(t.detail.x, t.detail.y, 0), snabbt.setElementTransform(e, i), 1 == this._imageHopper.dragPath.length && (e.style.opacity = 1, snabbt(e.firstChild, {
                        fromOpacity: 0,
                        opacity: .5,
                        fromScale: [0, 0],
                        scale: [.5, .5],
                        duration: 150
                    }))
                }
            }, {
                key: "_onFileOut",
                value: function(t) {
                    this._removeState("file-over"), this._removeState("file-invalid"), this._removeError();
                    var e = this._getDrip(),
                        i = snabbt.createMatrix();
                    i.translate(t.detail.x, t.detail.y, 0), snabbt.setElementTransform(e, i), snabbt(e.firstChild, {
                        fromScale: [.5, .5],
                        scale: [0, 0],
                        fromOpacity: .5,
                        opacity: 0,
                        duration: 150,
                        complete: function() {
                            resetTransforms(e.firstChild)
                        }
                    })
                }
            }, {
                key: "_onDropFile",
                value: function(t) {
                    var e = this;
                    this._removeState("file-over");
                    var i = this._getDrip(),
                        n = snabbt.createMatrix();
                    n.translate(t.detail.x, t.detail.y, 0), snabbt.setElementTransform(i, n);
                    var a = this._imageHopper.dragPath.length,
                        o = this._imageHopper.dragPath[a - Math.min(10, a)],
                        r = t.detail.x - o.x,
                        s = t.detail.y - o.y;
                    snabbt(i, {
                        fromPosition: [t.detail.x, t.detail.y, 0],
                        position: [t.detail.x + r, t.detail.y + s, 0],
                        duration: 200
                    }), snabbt(i.firstChild, {
                        fromScale: [.5, .5],
                        scale: [2, 2],
                        fromOpacity: 1,
                        opacity: 0,
                        duration: 200,
                        complete: function() {
                            resetTransforms(i.firstChild), e._load(t.target.files[0])
                        }
                    })
                }
            }, {
                key: "_onChange",
                value: function(t) {
                    this._load(t.target.files[0])
                }
            }, {
                key: "_load",
                value: function(t, e) {
                    var i = this,
                        n = getFileMetaData(t);
                    return n.size && this._options.maxFileSize && bytesToMegaBytes(n.size) > this._options.maxFileSize ? (this._onOverWeightFile(), void(e && e("file-too-big"))) : (this._removeState("empty"), this._imageHopper && (this._imageHopper.enabled = !1), this._data.input.name = n.name, this._data.input.type = n.type, this._data.input.size = n.size, this._startProgressLoop(), this._addState("busy"), void getImageAsCanvas(t, function(t, a) {
                        return t ? (t.setAttribute("data-file", n.name), void i._loadCanvas(t, function() {
                            var t = i._getIntro();
                            snabbt(t, {
                                fromScale: [1.25, 1.25],
                                scale: [1, 1],
                                fromOpacity: 0,
                                opacity: 1,
                                easing: "spring",
                                springConstant: .3,
                                springDeceleration: .7,
                                complete: function() {
                                    resetTransforms(t), i._showButtons(), i._stopProgressLoop(), i._removeState("busy"), i._addState("preview"), e && e()
                                }
                            })
                        })) : (i._removeState("busy"), i._stopProgressLoop(), i._resetData(), void(e && e("file-not-found")))
                    }))
                }
            }, {
                key: "_loadCanvas",
                value: function(t, e) {
                    var i = this;
                    this._isFixedRatio() || (this._ratio = t.height / t.width, this._scaleDropArea(this._ratio)), this._data.input.image = t, this._data.input.width = t.width, this._data.input.height = t.height, this._data.actions.crop = getAutoCropRect(t.width, t.height, this._ratio), this._options.size && (this._data.actions.size = {
                        width: this._options.size.width,
                        height: this._options.size.height
                    }), this._applyTransforms(t, function(t) {
                        var n = i._getIntro(),
                            a = n.offsetWidth / t.width,
                            o = i._options.service && i._options.push && i._getSlimType() !== SlimType.IMG;
                        i._save(function() {}, o), n.src = cloneCanvasScaled(t, a).toDataURL(), n.onload = function() {
                            n.onload = null, e && e()
                        }
                    })
                }
            }, {
                key: "_applyTransforms",
                value: function(t, e) {
                    var i = this;
                    transformCanvas(t, this._data.actions, function(t) {
                        i._data.output.width = t.width, i._data.output.height = t.height, i._data.output.image = t, i._onTransformCanvas(i._data, function(t) {
                            i._data = t, e(i._data.output.image)
                        })
                    })
                }
            }, {
                key: "_onTransformCanvas",
                value: function(t, e) {
                    this._options.onTransform(cloneData(t), e)
                }
            }, {
                key: "_appendEditor",
                value: function() {
                    this._imageEditor || (this._imageEditor = new ImageEditor(create("div"), {
                        minSize: this._options.minSize,
                        buttonConfirmClassName: "slim-btn-confirm",
                        buttonCancelClassName: "slim-btn-cancel",
                        buttonConfirmLabel: this._options.buttonConfirmLabel,
                        buttonCancelLabel: this._options.buttonCancelLabel,
                        buttonConfirmTitle: this._options.buttonConfirmTitle,
                        buttonCancelTitle: this._options.buttonCancelTitle
                    }), this._imageEditor.element.addEventListener("cancel", this), this._imageEditor.element.addEventListener("confirm", this))
                }
            }, {
                key: "_scaleDropArea",
                value: function(t) {
                    this._input.style.marginBottom = 100 * t + "%", this._element.setAttribute("data-ratio", "1:" + t);
                }
            }, {
                key: "_onCancel",
                value: function(t) {
                    this._removeState("editor"), this._showButtons(), this._hideEditor()
                }
            }, {
                key: "_onConfirm",
                value: function(t) {
                    var e = this;
                    this._removeState("editor"), this._startProgressLoop(), this._addState("busy"), this._data.actions.crop = t.detail.crop, this._applyTransforms(this._data.input.image, function(t) {
                        var i = e._getInOut(),
                            n = "out" === i[0].className ? i[0] : i[1],
                            a = n === i[0] ? i[1] : i[0];
                        n.className = "in", n.style.opacity = "0", n.style.zIndex = "2", a.className = "out", a.style.zIndex = "1", n.src = cloneCanvasScaled(t, n.offsetWidth / t.width).toDataURL(), "free" === e._options.ratio && (e._ratio = n.naturalHeight / n.naturalWidth, e._scaleDropArea(e._ratio)), e._hideEditor(), setTimeout(function() {
                            e._showPreview(n, function() {
                                var t = e._options.service && e._options.push;
                                e._save(function(t, i) {
                                    e._toggleButton("upload", !0), e._stopProgressLoop(), e._removeState("busy"), e._showButtons(), i && i.path && (n.src = i.path)
                                }, t)
                            })
                        }, 250)
                    })
                }
            }, {
                key: "_save",
                value: function() {
                    var t = this,
                        e = arguments.length <= 0 || void 0 === arguments[0] ? function() {} : arguments[0],
                        i = arguments.length <= 1 || void 0 === arguments[1] ? !0 : arguments[1],
                        n = flattenData(this._data, this._options.post);
                    this._options.onSave(n, function(n) {
                        t._output && t._store(n), t._options.service && i && t._upload(n, function(t, i) {
                            e(t, i)
                        }), t._options.service && i || e()
                    })
                }
            }, {
                key: "_store",
                value: function(t) {
                    this._isRequired && (this._input.required = !1), this._output.value = JSON.stringify(t)
                }
            }, {
                key: "_upload",
                value: function(t, e) {
                    var i = this,
                        n = new FormData;
                    n.append(this._output.name, JSON.stringify(t));
                    var a = this._element.querySelector(".slim-upload-status");
                    send(this._options.service, n, function(t, e) {
                        i._updateProgress(t / e)
                    }, function(t) {
                        setTimeout(function() {
                            a.innerHTML = i._options.statusUploadSuccess, a.setAttribute("data-state", "success"), a.style.opacity = 1, setTimeout(function() {
                                a.style.opacity = 0
                            }, 2e3)
                        }, 250), e(null, t)
                    }, function(t) {
                        var n = "";
                        n = "file-too-big" === t ? i._options.statusContentLength : "invalid-json" === t ? i._options.statusInvalidResponse : i._options.statusUnknownResponse, setTimeout(function() {
                            a.innerHTML = n, a.setAttribute("data-state", "error"), a.style.opacity = 1
                        }, 250), e(t)
                    })
                }
            }, {
                key: "_showEditor",
                value: function() {
                    SlimPopover.show(), this._imageEditor.show()
                }
            }, {
                key: "_hideEditor",
                value: function() {
                    this._imageEditor.hide(), setTimeout(function() {
                        SlimPopover.hide()
                    }, 250)
                }
            }, {
                key: "_showPreview",
                value: function(t, e) {
                    snabbt(t, {
                        fromPosition: [0, 50, 0],
                        position: [0, 0, 0],
                        fromScale: [1.5, 1.5],
                        scale: [1, 1],
                        fromOpacity: 0,
                        opacity: 1,
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .7,
                        complete: function() {
                            resetTransforms(t), e && e()
                        }
                    })
                }
            }, {
                key: "_hideResult",
                value: function(t) {
                    var e = this._getIntro();
                    e && snabbt(e, {
                        fromScale: [1, 1],
                        scale: [.5, .5],
                        fromOpacity: 1,
                        opacity: 0,
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .75,
                        complete: function() {
                            resetTransforms(e), t && t()
                        }
                    })
                }
            }, {
                key: "_showButtons",
                value: function(t) {
                    this._btnGroup.style.display = "", snabbt(this._btnGroup.childNodes, {
                        fromScale: [.5, .5],
                        scale: [1, 1],
                        fromPosition: [0, 10, 0],
                        position: [0, 0, 0],
                        fromOpacity: 0,
                        opacity: 1,
                        delay: function(t) {
                            return 250 + 50 * t
                        },
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .85,
                        complete: function() {
                            resetTransforms(this)
                        },
                        allDone: function() {
                            t && t()
                        }
                    })
                }
            }, {
                key: "_hideButtons",
                value: function(t) {
                    var e = this;
                    snabbt(this._btnGroup.childNodes, {
                        fromScale: [1, 1],
                        scale: [.85, .85],
                        fromOpacity: 1,
                        opacity: 0,
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .75,
                        allDone: function() {
                            e._btnGroup.style.display = "none", t && t()
                        }
                    })
                }
            }, {
                key: "_hideStatus",
                value: function() {
                    var t = this._element.querySelector(".slim-upload-status");
                    t.style.opacity = 0
                }
            }, {
                key: "_doEdit",
                value: function() {
                    var t = this;
                    this._data.input.image && (this._addState("editor"), this._imageEditor || this._appendEditor(), SlimPopover.inner = this._imageEditor.element, this._imageEditor.open(cloneCanvas(this._data.input.image), "free" === this._options.ratio ? null : this._ratio, function() {
                        t._showEditor(), t._hideButtons(), t._hideStatus()
                    }))
                }
            }, {
                key: "_doReset",
                value: function() {
                    var t = this;
                    this._clearState(), this._addState("empty"), this._imageHopper.enabled = !0, this._isRequired && (this._input.required = !0);
                    var e = this._getOutro();
                    e && (e.style.opacity = "0"), this._resetData(), setTimeout(function() {
                        t._hideButtons(function() {
                            t._toggleButton("upload", !0)
                        }), t._hideStatus(), t._hideResult()
                    }, 250)
                }
            }, {
                key: "_doUpload",
                value: function(t) {
                    var e = this;
                    this._data.input.image && (this._addState("upload"), this._startProgress(), this._hideButtons(function() {
                        e._toggleButton("upload", !1), e._save(function(i, n) {
                            e._removeState("upload"), e._stopProgress(), t && t(i, n), i && e._toggleButton("upload", !0), e._showButtons()
                        })
                    }))
                }
            }, {
                key: "_doDownload",
                value: function() {
                    var t = this._data.output.image;
                    if (t) {
                        var e = this._data.input.name,
                            i = this._data.input.type;
                        t.toBlob(function(t) {
                            var i = create("a");
                            i.download = e, i.href = URL.createObjectURL(t), i.click()
                        }, i)
                    }
                }
            }, {
                key: "_doDestroy",
                value: function() {
                    var t = this;
                    if (this._imageHopper && (HopperEvents.forEach(function(e) {
                            t._imageHopper.element.removeEventListener(e, t)
                        }), this._imageHopper.destroy()), this._imageEditor && (this._imageEditor.element.removeEventListener("cancel", this), this._imageEditor.element.removeEventListener("confirm", this), this._imageEditor.destroy()), nodeListToArray(this._btnGroup.children).forEach(function(e) {
                            e.removeEventListener("click", t)
                        }), this._input.removeEventListener("change", this), this._input.style.removeProperty("padding-bottom"), this._autoInput && removeElement(this._input), this._autoOutput && removeElement(this._output), this._getSlimType() !== SlimType.IMG && (this._input.name = this._output.name), this._autoAcceptCommonMimeTypes && this._input.removeAttribute("accept"), this._element !== this._originalElement) {
                        var e = this._element.className;
                        this._element.parentNode.replaceChild(this._originalElement, this._element), this._originalElement.className = e, this._originalElement.classList.remove("slim"), this._originalElement.classList.length || this._originalElement.removeAttribute("class")
                    } else {
                        this._element.removeAttribute("data-state"), removeElement(this._btnGroup);
                        var i = this._element.querySelector(".slim-area");
                        removeElement(i)
                    }
                }
            }], [{
                key: "options",
                value: function() {
                    var t = {
                        edit: !0,
                        ratio: "free",
                        size: null,
                        post: ["output", "actions"],
                        service: null,
                        push: !1,
                        defaultInputName: "slim[]",
                        minSize: {
                            width: 100,
                            height: 100
                        },
                        maxFileSize: null,
                        download: !1,
                        label: "<p>Drop your image here</p>",
                        statusFileType: "<p>Invalid file type, expects: $0.</p>",
                        statusFileSize: "<p>File is too big, maximum file size: $0 MB.</p>",
                        statusNoSupport: "<p>Your browser does not support image cropping.</p>",
                        statusContentLength: '<span class="slim-upload-status-icon"></span> The file is probably too big',
                        statusInvalidResponse: '<span class="slim-upload-status-icon"></span> The server returned an invalid response',
                        statusUnknownResponse: '<span class="slim-upload-status-icon"></span> An unknown error occurred',
                        statusUploadSuccess: '<span class="slim-upload-status-icon"></span> Saved',
                        onTransform: function(t, e) {
                            e(t)
                        },
                        onSave: function(t, e) {
                            e(t)
                        }
                    };
                    return SlimButtons.concat(ImageEditorButtons).forEach(function(e) {
                        var i = capitalizeFirstLetter(e);
                        t["button" + i + "ClassName"] = null, t["button" + i + "Label"] = i, t["button" + i + "Title"] = null
                    }), t
                }
            }]), t
        }();
    ! function() {
        function t(t, e) {
            return t.getAttribute("data-" + e)
        }

        function e(t) {
            return t.replace(/([a-z](?=[A-Z]))/g, "$1-").toLowerCase()
        }

        function i(t) {
            return "object" == typeof t ? JSON.parse(JSON.stringify(t)) : t
        }

        function n(t) {
            return t ? "<p>" + t + "</p>" : null
        }

        function a(t) {
            var e = window,
                i = t.split(".");
            return i.forEach(function(t, n) {
                e = e[i[n]]
            }), e
        }
        var o = [],
            r = function(t) {
                for (var e = 0, i = o.length; i > e; e++)
                    if (o[e].isAttachedTo(t)) return e;
                return -1
            },
            s = function(t, e) {
                return e
            },
            l = function(t, e) {
                return "true" === e
            },
            h = function(t, e) {
                return e ? "true" === e : !0
            },
            u = function(t, e) {
                return n(e)
            },
            c = function(t, e) {
                return e ? a(e) : null
            },
            d = function(t, e) {
                if (!e) return null;
                var i = intSplit(e, ",");
                return {
                    width: i[0],
                    height: i[1]
                }
            },
            p = {
                download: l,
                edit: h,
                minSize: d,
                size: d,
                service: function(t, e) {
                    return "undefined" == typeof e ? null : e
                },
                push: l,
                post: function(t, e) {
                    return e ? e.split(",").map(function(t) {
                        return t.trim()
                    }) : null
                },
                defaultInputName: s,
                ratio: function(t, e) {
                    return e ? e : null
                },
                maxFileSize: function(t, e) {
                    return e ? parseFloat(e) : null
                },
                label: u,
                statusFileSize: u,
                statusFileType: u,
                statusNoSupport: u,
                statusContentLength: s,
                statusInvalidResponse: s,
                statusUnknownResponse: s,
                statusUploadSuccess: s,
                onTransform: c,
                onSave: c
            };
        SlimButtons.concat(ImageEditorButtons).forEach(function(t) {
            var e = capitalizeFirstLetter(t);
            p["button" + e + "ClassName"] = s, p["button" + e + "Label"] = s, p["button" + e + "Title"] = s
        }), Slim.supported = function() {
            return "undefined" != typeof window.FileReader
        }(), Slim.parse = function(n) {
            var a, o, r, s, l, h;
            for (a = n.querySelectorAll(".slim:not([data-state])"), r = a.length; r--;) {
                o = a[r], s = {};
                for (h in p) p.hasOwnProperty(h) && (l = p[h](o, t(o, e(h))), s[h] = null === l ? i(Slim.options()[h]) : l);
                Slim.create(o, s)
            }
        }, Slim.find = function(t) {
            var e = o.filter(function(e) {
                return e.isAttachedTo(t)
            });
            return e ? e[0] : null
        }, Slim.create = function(t, e) {
            if (!Slim.find(t)) {
                var i = new Slim(t, e);
                o.push(i)
            }
        }, Slim.destroy = function(t) {
            var e = r(t);
            return 0 > e ? !1 : (o[e].destroy(), o[e] = null, o.splice(e, 1), !0)
        }
    }();
    return Slim;
}();