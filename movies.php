<?php 

class Ldap {

	public $top = 'media/movies/';

	public function __construct() {
		
	}

    private function api($action) {
        $retval = ['api' => True];
        switch($action) {
            case 'lookup_dir':
                $files = $this->get_dir_list($_REQUEST['name']);
                $retval['files'] = $files;
                break;
        }

        return $retval;
    }

	public function init() {

		if(isset($_REQUEST['action'])){
            return $this->api($_REQUEST['action']);
		}

		$files = $this->get_dir_list($this->top);
		$init_html = '<script>var files = '.json_encode($files).';</script>';
		return $init_html;
	}

    /**
     * @param $dir
     *
     * This function is vulnerable on an open port.
     * Can be fed any directory.
     *
     * Can force a directory prefix.
     * Can switch to database driven file locations.
     * Can lock down read permissions of user running application.
     * Maybe all 3? :D
     *
     * @return array
     */
	public function get_dir_list($dir) {
        // Get Top Directory and Show on initial page render
        $files = self::open_dir($dir);
        $ret_json = [];
        foreach($files as $k => $v) {
            if(in_array($v, ['.', '..'])) {
                continue;
            }
            $dir_name = $dir . $v;
            $is_dir = self::check_dir($dir_name);

            if($is_dir) $dir_name .= '/';

            $ret_json[] = ['name' => $dir_name,
                            'is_dir' => $is_dir];
        }
        return $ret_json;
	}

	public static function check_dir($input) {
		return is_dir($input);
	}

	public static function open_dir($dir) {
		return scandir($dir);
	}
	
}

$ldap = new Ldap();
$response = $ldap->init();

if(isset($response['api']) && $response['api'] === True && isset($response['files'])) {
    echo json_encode($response['files']);
    return true;
}

?>

<HTML>
	<head>
		<style>
			body {background: #262626;}
			
			video {width: 98vw; height: 80vh;}

			.gamepad-status {
				color: #FFF;
				padding: 5px;
				text-transform: uppercase;
			}

			.item {
				float: left;
				width: 150px;
				height: 150px;
				word-wrap: break-word;
				padding: 5px;
				border: 1px solid #FFF;
				background: #FFF;
				margin: 8px;
				cursor: pointer;
			}
			
			.item.selected {
				border: 1px solid red;
			}
		</style>
	</head>

	<body>
		<div class="gamepad-status"></div>
		<div class="crumb"></div>
		<div class="main-content">
			
		</div>

		<!-- Echos Script Tag -->
		<?php echo $response ?>

<script src="/assets/js/jquery-3.1.0.min.js"></script>
<script>
	var ldap = {
		current_dir : 'media/movies/',

		selectItem : function(c) {
            var self = this;
            var name = c.attr('data-name');
			window.history.pushState({name: name}, "page", '?path=' + name);
            if(c.hasClass('dir')) {
                self.getPath(name);
            } else {
				self.putVideo(name)
            }
        },
		putVideo : function(name) {
			var _main_content = $('.main-content');
			_main_content.empty();
			_main_content.append('<video src="'+name+'" type="video/mp4" controls>');
		},

		getPath : function(path) {
			var self = this;
			self.current_dir = path;
			$.ajax({
				url : '/',
				data : {name: path, action : 'lookup_dir'},
				dataType: 'json',
				success : function(r) {
					if($.isEmptyObject(r)) {
						self.putVideo(path);
					} else {
						self.showFiles(r);
					}
				}
			});
		},

		showFiles : function(files) {
			var self = this;
			$('.main-content').empty();
			$.each(files, function(ind, val) {                                                       
         			var html_class='file';                                                           
         			if(val['is_dir']) {                                                              
                 			html_class='dir'                                                         
         			}                                                                                
         			var _div = $('<div class="item '+html_class+'">').attr('data-name', val['name']);
         			_div.html(val['name']);

         			$('.main-content').append(_div);                                                 
	 		});

			$('.item').on('click', function() {
				self.selectItem($(this));
			});
		},

		parseUrl : function() {
			var self = this;
			var regex = /[?&]([^=#]+)=([^&#]*)/g,
				url = window.location.href,
				params = {},
				match;
			while(match = regex.exec(url)) {
				params[match[1]] = match[2];
			}

			if(params['path'] !== undefined) {
				self.getPath(params['path']);
				return true;
			}

			return false;
		},

		init : function() {
			var self = this;

			var has_path = self.parseUrl();
			if(!has_path) {
				self.showFiles(files);
			}

			$(window).bind('popstate', function(e) {
				var state = e.originalEvent.state;
				var path = 'videos/';
				if(state != null) {
					path = state['name'];
				}

				self.getPath(path);
			});
		}
	};
	
	function reportOnGamepad() {
		var gp = navigator.getGamepads()[0];
		var html = "";
		html += "id: "+gp.id+"<br/>";
 
		for(var i=0;i<gp.buttons.length;i++) {
			html+= "Button "+(i+1)+": ";
            if(gp.buttons[i].pressed){
                html+= " pressed";
            }
            html+= "<br/>";
        }
 
        for(var i=0;i<gp.axes.length; i+=2) {
            html+= "Stick "+(Math.ceil(i/2)+1)+": "+gp.axes[i]+","+gp.axes[i+1]+"<br/>";
        }

        $(".gamepad-status").html(html);
    }

	var gp = {
		btn_interval : 0,
		count : 1,

        canGame : function() {
            return "getGamepads" in navigator;
        },

        initController : function() {
            var hasGP = false;
            var _gamepad_status = $('.gamepad-status');
            if(gp.canGame()) {
                var prompt = "To begin using your gamepad, connect it and press any button!";
                _gamepad_status.text(prompt);

                $(window).on("gamepadconnected", function() {
                    hasGP = true;
                    _gamepad_status.html("Gamepad connected!");
                    //repGP = window.setInterval(reportOnGamepad,100);
                    gp.bindActions();
                });

                $(window).on("gamepaddisconnected", function() {
                    _gamepad_status.text(prompt);
                });

                //setup an interval for Chrome
                var checkGP = window.setInterval(function() {
                    if(navigator.getGamepads()[0]) {
                        if(!hasGP) $(window).trigger("gamepadconnected");
                        window.clearInterval(checkGP);
                    }
                }, 500);
            }
        },

		bindActions : function() {
			var self = this;
            var _item = $('.item');

            // On interval, check what buttons are being pressed
            // TODO:: Implement Status checking to prevent misfiring button events as other events are processing
            // Do this in such a way that pressing left arrow will not result in [A] button being unresponsive
			window.clearInterval(self.btn_interval);
			self.btn_interval = window.setInterval(function(){
				self.gp = navigator.getGamepads()[0];
				if(self.gp.buttons[15].pressed) {
					self.count++;
				} else if(self.gp.buttons[14].pressed) {
					self.count -= 1;
				}
				
				var total = _item.length;
				if(self.gp.buttons[15].pressed || self.gp.buttons[14].pressed) {
					
					if(self.count < 1) {
						self.count = total + 1;
					} else if(self.count > total) {
						self.count = 0;
					}
					_item.removeClass('selected');
                    $('.item:nth-child('+self.count+')').addClass('selected');
				}

                // [A] button pressed
				if(self.gp.buttons[0].pressed) {
					var _sel = $('.item.selected');
                    ldap.selectItem(_sel);
					self.bindActions();
					self.count = 0;
				}

                // [B] Button pressed
				if(self.gp.buttons[1].pressed) {
					window.history.back();
				}
			}, 100)
		}
	};

	$(document).ready(function() {
        gp.initController();
		ldap.init();
	});

</script>
</body>
</HTML>