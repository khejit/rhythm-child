(function() {
        var center_elm, smoothstep, wait_until_font_loaded, bind = function(fn, me) {
            return function() {
                return fn.apply(me, arguments)
            }
        }
            , extend = function(child, parent) {
            for (var key in parent) {
                if (hasProp.call(parent, key))
                    child[key] = parent[key]
            }
            function ctor() {
                this.constructor = child
            }
            ctor.prototype = parent.prototype;
            child.prototype = new ctor;
            child.__super__ = parent.prototype;
            return child
        }
            , hasProp = {}.hasOwnProperty;
        smoothstep = function(t) {
            return t * t * t * (t * (t * 6 - 15) + 10)
        }
        ;
        center_elm = function(el) {
            return el.css({
                "margin-left": -el.outerWidth() / 2 + "px",
                "margin-top": -el.outerHeight() / 2 + "px"
            })
        }
        ;
        wait_until_font_loaded = function(el, fn, min_size) {
            var check, icon, timeout, times;
            if (min_size == null ) {
                min_size = 10
            }
            icon = el.find(".icon");
            if (!icon.length) {
                return fn()
            }
            if (icon.width() === 0) {
                setTimeout(fn, 100);
                return
            }
            timeout = 10;
            times = 20;
            check = function() {
                times -= 1;
                timeout += 2;
                if (icon.width() > min_size) {
                    return fn()
                } else if (times > 0) {
                    return setTimeout(check, timeout)
                }
            }
            ;
            return setTimeout(check, timeout)
        }
        ;
        I.EmbedBuyForm = function(superClass) {
            extend(EmbedBuyForm, superClass);
            EmbedBuyForm.prototype.event_category = "embed_game";
            EmbedBuyForm.prototype.submit_handler = function() {
                if (this.form.attr("method") === "GET") {
                    if (!this.is_valid()) {
                        return false
                    }
                    this.el.trigger("i:buy_complete", []);
                    return
                }
                return this.remote_submit.apply(this, arguments)
            }
            ;
            EmbedBuyForm.prototype.download_url = function() {
                return "/game/download_url/" + this.game.id
            }
            ;
            EmbedBuyForm.prototype.set_error = function(err) {
                err = this._clean_error_message(err);
                return this.embed_widget.show_error(err)
            }
            ;
            function EmbedBuyForm(el, game, embed_widget) {
                this.embed_widget = embed_widget;
                this.checkout_btn_handler = bind(this.checkout_btn_handler, this);
                this.set_error = bind(this.set_error, this);
                this.download_url = bind(this.download_url, this);
                this.submit_handler = bind(this.submit_handler, this);
                EmbedBuyForm.__super__.constructor.call(this, el, game)
            }
            EmbedBuyForm.prototype.checkout_btn_handler = function(e) {
                var source;
                source = this.set_source(e);
                if (source === "amazon") {
                    this.show_email_capture();
                    return false
                }
            }
            ;
            return EmbedBuyForm
        }(I.BuyForm);
        I.EmbedWidget = function() {
            EmbedWidget.prototype.page_p = 0;
            EmbedWidget.prototype.animation_duration = 500;
            EmbedWidget.prototype.template = I.lazy_template(EmbedWidget, "embed_error");
            function EmbedWidget(el) {
                this.toggle_checkout = bind(this.toggle_checkout, this);
                var title;
                this.el = $(el);
                this.game = this.el.data("game");
                this.setup_tracking();
                this.first_page = this.el.find(".first_page");
                this.second_page = this.el.find(".second_page");
                this.first_page_shroud = this.first_page.find(".page_shroud");
                this.second_page_shroud = this.second_page.find(".page_shroud");
                this.left_column = this.second_page.find(".left_column");
                this.buy = new I.EmbedBuyForm(this.second_page,this.game,this);
                if (Modernizr.csstransforms3d) {
                    this.prefixed_transform = Modernizr.prefixed("transform")
                } else {
                    this.update_transition = this.slide_transition
                }
                this.el.dispatch("click", {
                    buy_btn: function(_this) {
                        return function() {
                            I.event("embed_game", "toggle_buy", "" + _this.game.id);
                            return _this.toggle()
                        }
                    }(this),
                    try_again_btn: function(_this) {
                        return function() {
                            _this.left_column.removeClass("completed_checkout");
                            return _this.buy.set_loading(false)
                        }
                    }(this),
                    back_page_btn: function(_this) {
                        return function() {
                            if (_this.left_column.is(".is_free:not(.show_free_message)")) {
                                return _this.toggle_checkout(false)
                            } else {
                                return _this.toggle()
                            }
                        }
                    }(this),
                    dismiss_error_btn: function(_this) {
                        return function() {
                            return _this.show_error(null )
                        }
                    }(this),
                    enter_price_btn: function(_this) {
                        return function() {
                            _this.toggle_checkout();
                            return _this.buy.input.focus()
                        }
                    }(this)
                });
                this.update_transition(this.page_p);
                this.el.find(".nano").nanoScroller({
                    preventPageScrolling: true
                });
                this.el.on("i:show_checkout", function(_this) {
                    return function() {
                        _this.toggle_checkout();
                        return _this.buy.input.focus()
                    }
                }(this));
                this.el.on("i:buy_complete", function(_this) {
                    return function() {
                        return _this.left_column.addClass("completed_checkout")
                    }
                }(this));
                title = this.first_page.find("h1");
                wait_until_font_loaded(title, function(_this) {
                    return function() {
                        return I.adjust_font_size_to_fit(title)
                    }
                }(this));
                I.adjust_font_size_to_fit(this.second_page.find("h2"))
            }
            EmbedWidget.prototype.setup_tracking = function() {
                if (document.referrer) {
                    return I.set_cookie("ref:game:" + this.game.id, "embed:" + document.referrer, {
                        expires: 1
                    })
                }
            }
            ;
            EmbedWidget.prototype.toggle_checkout = function(show) {
                if (show == null ) {
                    show = true
                }
                if (show) {
                    return this.left_column.removeClass("show_free_message").find(".skip_pay_area").slideUp()
                } else {
                    return this.left_column.find(".skip_pay_area").slideDown(function(_this) {
                        return function() {
                            return _this.left_column.addClass("show_free_message")
                        }
                    }(this))
                }
            }
            ;
            EmbedWidget.prototype.show_error = function(text) {
                this.el.find(".error_box").remove();
                if (text != null ) {
                    center_elm($(this.template({
                        text: text
                    })).appendTo(this.el));
                    return this.el.addClass("has_error")
                } else {
                    return this.el.removeClass("has_error")
                }
            }
            ;
            EmbedWidget.prototype.toggle = function() {
                var obj, to;
                obj = {
                    p: this.page_p
                };
                if (this.page_p < .5) {
                    to = {
                        p: 1
                    }
                } else {
                    to = {
                        p: 0
                    }
                }
                return $(obj).animate(to, {
                    duration: this.animation_duration,
                    progress: function(_this) {
                        return function() {
                            _this.update_transition(obj.p);
                            return _this.page_p = obj.p
                        }
                    }(this)
                })
            }
            ;
            EmbedWidget.prototype.update_transition = function(p) {
                return this.rotate_transition(p)
            }
            ;
            EmbedWidget.prototype.slide_transition = function(p) {
                var first_page_height;
                p = smoothstep(p);
                first_page_height = this.first_page.outerHeight(true);
                this.first_page.css({
                    top: p * -1 * first_page_height + "px",
                    display: p === 1 ? "none" : "block"
                });
                this.second_page.css({
                    top: (1 - p) * first_page_height + "px",
                    display: p === 0 ? "none" : "block"
                });
                return this.fade_transition(p)
            }
            ;
            EmbedWidget.prototype.rotate_transition = function(p) {
                var first_css, first_page_transform, first_z, rot, second_css, second_page_transform, second_z, zscale, ztranslate;
                zscale = smoothstep(1 - Math.abs(p - .5) * 2);
                ztranslate = zscale * -150;
                if (p < .5) {
                    first_z = 1;
                    second_z = 0
                } else {
                    first_z = 0;
                    second_z = 1
                }
                rot = p * 90;
                first_page_transform = "translatez(" + ztranslate + "px) rotateX(" + rot + "deg)";
                second_page_transform = "translatez(" + ztranslate + "px) rotateX(" + (rot - 90) + "deg)";
                if (p === 0) {
                    first_page_transform = "";
                    second_page_transform = ""
                }
                if (p === 1) {
                    first_page_transform = "";
                    second_page_transform = ""
                }
                first_css = {
                    "z-index": first_z
                };
                first_css[this.prefixed_transform] = first_page_transform;
                this.first_page.css(first_css);
                second_css = {
                    "z-index": second_z
                };
                second_css[this.prefixed_transform] = second_page_transform;
                this.second_page.css(second_css);
                return this.fade_transition(p)
            }
            ;
            EmbedWidget.prototype.fade_transition = function(p) {
                var first_opacity, second_opacity;
                first_opacity = p * .5;
                second_opacity = (1 - p) * .5;
                first_opacity = Math.round(first_opacity * 100) / 100;
                second_opacity = Math.round(second_opacity * 100) / 100;
                if (p === 0) {
                    this.first_page_shroud.hide()
                } else {
                    this.first_page_shroud.css("opacity", first_opacity).show()
                }
                if (p === 1) {
                    return this.second_page_shroud.hide()
                } else {
                    return this.second_page_shroud.css("opacity", second_opacity).show()
                }
            }
            ;
            return EmbedWidget
        }()
    }
).call(this);
