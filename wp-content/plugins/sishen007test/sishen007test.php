<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 2018/10/26
 * Time: 14:50
 */
/*
Plugin Name: Sishen007 ShowHead
Plugin URI: http://www.cnblogs.com/fxmbz/p/4059678.html
Description: 我制作的第一个WP插件,这个插件就是在后台页面的头部显示一段文字
Author: sishen007
Version: 1.0
Author URI: http://www.cnblogs.com/fxmbz
*/


class smzdm_first
{
    const MY_PLUGIN_VERSION_NUM = '1.0';
    const MY_PLUGIN_MINIMUM_WP_VERSION = '4.0';
    protected static $myNewTable = '';

    public function init()
    {
//        global $wpdb;
//        self::$myNewTable = $wpdb->prefix . 'mynewtable';
//        register_activation_hook(__FILE__, [$this, 'plugin_activation_cretable']);
        // 插件停用时，运行回调方法删除数据表，删除options表中的插件版本号
//        register_deactivation_hook(__FILE__, [$this, 'plugin_deactivation_deltable']);
//        register_activation_hook(__FILE__, [$this, 'plugin_activation_insertdate']);
        // 当加载插件时，运行回调方法检查插件版本是否有更新,
//        add_action('plugins_loaded', [$this, 'myplugin_update_db_check']);
//        add_action('admin_head',[$this,'my_first_plugin']);
//        add_action('comment_text', [$this, 'add_copyright_info']);
//        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('admin_menu', [$this, 'comments_submenu']);
        add_action('admin_menu', [$this, 'baw_create_menu']);
        // 当wp后台的头部加载时，执行的 PHP函数 my_custom_admin_head
//        add_action('admin_head', [$this, 'my_custom_admin_head']);


        // 当评论文本内容还没有展示在页面之前,执行自定义的过滤函数来过滤内容中的敏感字
//        add_filter('the_comment', [$this, 'my_filter_word']);

        // 使用 widgets_init 动作钩子 注册小工具
//        add_action('widgets_init', [$this, 'sishen_register_widgets']);

        //需要给 add_meta_boxes 钩子，挂载一个自定义的方法
//        add_action('add_meta_boxes', [$this, 'myplugin_add_meta_box']);
        //文章保存的时候，会调用 save_post 钩子，因此我们要借助这个钩子来保存元数据框内的数据
//        add_action('save_post', [$this, 'myplugin_save_meta_box_data']);
//        register_activation_hook(__FILE__, [$this, 'plugin_activation_createtest']);
//        add_action('admin_menu', [$this, 'create_test_menu']);

        // [使用WordPress的API保存插件 也可以选项添加到现有管理界面]
        add_action('admin_menu', [$this, 'create_apisaveplugin_menu']);
        add_action('admin_init', [$this, 'register_sishen_test_setting']);
        // [调用ajax]
//        add_action('wp_head', array($this, 'sishen_test_head_fun'));
        //使用ajax校验信息
//        add_action('wp_enqueue_scripts', [$this, 'my_scripts']);
//        add_action('admin_enqueue_scripts', [$this, 'my_scripts']);
//        add_action('wp_ajax_color_check_action', array($this, 'color_check_action_fun'));
//        add_action('wp_ajax_nopriv_hcsem_description', array($this, 'hcsem_description_fun'));

        // [本地化 翻译文件]
//        add_action( 'init', array( $this, 'hcsem_load_textdomain' ) );
        // [插件权限控制]

        //添加一个 sishen 短标签，调用 hcsem_shortcode 方法进行处理
//		add_shortcode( 'sishen', array( $this, 'sishen_shortcode' ) );
    }
    // [短标签]
    public function sishen_shortcode($atts){
        $atts = shortcode_atts( array(
                'title' => '《SEO的道与术》',
                'url' => 'http://product.dangdang.com/23709551.html',
                'img' => 'http://images0.cnblogs.com/blog2015/121863/201505/272034378914366.png'
            ), $atts, 'sishen_shortcode' );

        $output = "<a href='{$atts['url']}' title='{$atts['title']}'>
                    <div class='file-box'>
                        <b>【{$atts['title']}】</b>
                        <div class='clr'></div>
                        <img src='{$atts['img']}' />
                        <div class='clr'></div>
                        <i>谢谢大家支持！</i>
                        <div class='clr'></div>
                    </div>
                </a>";

		return $output;
    }
    //
    /**
      * [本地化 翻译文件]
      * 翻译文件(创建MO文件): http://poedit.net/ 下载工具进行查看
      */
    public function hcsem_load_textdomain(){
        //加载 languages 目录下的翻译文件
		$currentLocale = get_locale();

		if( !empty($currentLocale) ) {

			$moFile = dirname(__FILE__) . "/languages/{$currentLocale}.mo";

			if( @file_exists( $moFile ) && is_readable( $moFile ) ) load_textdomain( 'sishen-test', $moFile );
		}
    }

    // [调用ajax]
    public function color_check_action_fun()
    {

        if (trim($_POST['color']) != "") {
            echo "ok";
        }
        wp_die();
    }

    // [调用ajax]
    public function hcsem_description_fun()
    {

        echo "死神的笔记本：" . $_POST['description'];
        wp_die();

    }

    // 加载scripts
    public function my_scripts()
    {
         // [插件权限控制]
        $screen = get_current_screen();
		if ( is_object( $screen ) && $screen->id == 'toplevel_page_sishen_test2' ) {
			wp_enqueue_script('sishen_test', plugins_url('js/sishen_test.js', __FILE__), array('jquery'));
            wp_localize_script('sishen_test', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
		}
    }

    // [调用ajax]
    public function sishen_test_head_fun()
    {
        $sishen_test_option = get_option("sishen_test_option");

        $bold = $sishen_test_option["bold"] == 1 ? "bold" : "normal";
        ?>
        <style>body {
            color: <?php echo $sishen_test_option["color"] ?>;
            font-size: <?php echo $sishen_test_option["size"] ?>px;
            font-weight: <?php echo $bold; ?>;
        }</style><?php
    }

    // [使用WordPress的API保存插件]
    public function register_sishen_test_setting()
    {
        // 注册一个选项，用于装载所有插件设置项
        $option_group = "sishen_test_group"; // 也可以选项添加到现有管理界面
        // 2、使用register_setting()注册要存储的字段
        register_setting($option_group, 'sishen_test_option');
        // 添加选项设置区域
        $setting_section = "sishen_test_setting_section";
        add_settings_section(
            $setting_section,
            '',
            '',
            $option_group
        );

        //设置字体颜色
        add_settings_field(
            'sishen_test_color',
            '字体颜色',
            [$this, 'sishen_test_color_function'],
            $option_group,
            $setting_section
        );

        //设置字体大小
        add_settings_field(
            'sishen_test_size',
            '字体大小',
            [$this, 'sishen_test_size_function'],
            $option_group,
            $setting_section
        );

        //设置字体加粗
        add_settings_field(
            'sishen_test_bold',
            '字体加粗',
            [$this, 'sishen_test_bold_function'],
            $option_group,
            $setting_section
        );
    }

    // [使用WordPress的API保存插件]
    public function sishen_test_color_function()
    {
        $sishen_test_option = get_option("sishen_test_option");
        ?>
        <input name='sishen_test_option[color]' type='text' value='<?php echo $sishen_test_option["color"]; ?>'/>
        <font id="error_color"></font></div>
        <?php
    }

    // [使用WordPress的API保存插件]
    public function sishen_test_size_function()
    {
        $sishen_test_option = get_option("sishen_test_option");
        $size = $sishen_test_option["size"];
        ?>
        <select name="sishen_test_option[size]">
            <option value="12" <? selected('12', $size); ?>>12</option>
            <option value="14" <? selected('14', $size); ?>>14</option>
            <option value="16" <? selected('16', $size); ?>>16</option>
            <option value="18" <? selected('18', $size); ?>>18</option>
            <option value="20" <? selected('20', $size); ?>>20</option>
        </select>
        <?php
    }

    // [使用WordPress的API保存插件]
    public function sishen_test_bold_function()
    {
        $sishen_test_option = get_option("sishen_test_option");
        ?>
        <input name="sishen_test_option[bold]" type="checkbox"
               value="1" <?php checked(1, $sishen_test_option["bold"]); ?> /> 加粗
        <?php
    }

    // [使用WordPress的API保存插件]
    public function create_apisaveplugin_menu()
    {
        //创建顶级菜单
        add_menu_page(
            '死神的插件首页2',
            '死神的插件2',
            'manage_options',
            'sishen_test2',
            [$this, 'sishen_settings_page2'],
            plugins_url('/images/icon.png', __FILE__)
        );
    }

    // [使用WordPress的API保存插件]
    public function sishen_settings_page2()
    {
        ?>
        <div class="wrap">
            <h2>插件顶级菜单</h2>

            <form action="options.php" method="post">
                <?php
                $option_group = "sishen_test_group";

                // 输出一些必要的字段，包括验证信息等
                settings_fields($option_group);

                //输出选项设置区域
                do_settings_sections($option_group);

                //输出按钮
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // [保存插件设置]
    public function create_test_menu()
    {
        //创建顶级菜单
        add_menu_page(
            '死神的插件首页',
            '死神的插件',
            'manage_options',
            'sishen_test',
            [$this, 'sishen_settings_page'],
            plugins_url('/images/icon.png', __FILE__)
        );
    }

    // [保存插件设置]
    public function sishen_settings_page()
    {
        global $wpdb;

        //当提交了，并且验证信息正确
        if (!empty($_POST) && check_admin_referer('sishen_test_nonce')) {

            //更新设置
            update_option('sishen_test_bold', $_POST['sishen_test_bold']);

            $wpdb->update("{$wpdb->prefix}test", array('color' => $_POST['color'], 'size' => $_POST['size']), array('id' => 1));
            echo '<div id="message" class="updated"><p><strong>保存成功！</strong></p></div>';
        }

        $sql = "SELECT * FROM `{$wpdb->prefix}test`";
        $row = $wpdb->get_row($sql, ARRAY_A);

        $color = $row['color'];
        $size = $row['size'];
        ?>
        <div class="wrap">
            <h2>插件顶级菜单</h2>

            <form action="" method="post">
                <p><label for="color">字体颜色：</label><input type="text" name="color" value="<?php echo $color; ?>"/></p>

                <p><label for="size">字体大小：</label>
                    <select name="size">
                        <option value="12" <? selected('12', $size); ?>>12</option>
                        <option value="14" <? selected('14', $size); ?>>14</option>
                        <option value="16" <? selected('16', $size); ?>>16</option>
                        <option value="18" <? selected('18', $size); ?>>18</option>
                        <option value="20" <? selected('20', $size); ?>>20</option>
                    </select></p>
                <p>
                    <label for="sishen_test_obold">字体加粗：</label>
                    <input name="sishen_test_bold" type="checkbox"
                           value="1" <?php checked(1, get_option('sishen_test_bold')); ?> />
                    加粗</p>

                <p><input type="submit" name="submit" value="保存设置"/></p>
                <?php
                //输出一个验证信息
                wp_nonce_field('sishen_test_nonce');
                ?>
            </form>
        </div>
        <?php
    }

    // [保存插件设置]创建插件test数据表
    public function plugin_activation_createtest()
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}test'") != "{$wpdb->prefix}test") {
            $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}test` (
			  `id` int(11) NOT NULL auto_increment COMMENT '编号',
			  `color` varchar(10) DEFAULT '' COMMENT '字体颜色',
			  `size`  varchar(10) DEFAULT '' COMMENT '字体大小',
			  PRIMARY KEY  (`id`)
			) DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
            $wpdb->query($sql);

            $sql = "REPLACE INTO `{$wpdb->prefix}test` VALUES (1, '#FF0000','20');";
            $wpdb->query($sql);
        }
    }

    // [元数据框]
    public function myplugin_save_meta_box_data($post_id)
    {

        //验证是否为有效信息
        if (!isset($_POST['myplugin_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['myplugin_meta_box_nonce'], 'myplugin_save_meta_box_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }

        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        if (!isset($_POST['_zzurl'])) {
            return;
        }

        $my_data = sanitize_text_field($_POST['_zzurl']);

        update_post_meta($post_id, '_zzurl', $my_data);
    }

    // [元数据框] 添加一个元数据框到 post 和 page 的管理界面中
    public function myplugin_add_meta_box()
    {
        $screens = array('post', 'page');

        add_meta_box(
            'myplugin_sectionid',
            '转载自',
            [$this, 'myplugin_meta_box_callback'],
            $screens
        );
    }

    // [元数据框] 元数据框展示代码
    public function myplugin_meta_box_callback($post)
    {
        // 添加一个验证信息，这个在保存元数据的时候用到
        wp_nonce_field('myplugin_save_meta_box_data', 'myplugin_meta_box_nonce');
        // 输出元数据信息
        $value = get_post_meta($post->ID, '_zzurl', true);
        echo '<label for="myplugin_new_field">';
        _e('本文章转载自：');
        echo '</label> ';
        echo '<input type="text" id="_zzurl" name="_zzurl" value="' . esc_attr($value) . '" size="25" />';
    }

    // [注册小工具]
    public function sishen_register_widgets()
    {
        register_widget('sishen_widget_info');
    }

    public function my_filter_word($content)
    {
        // 声明全局变量，来存储需要过滤的关键字
        global $shieldingword;
        $shieldingword = array('fuck', 'dirty'); // 需要过滤的关键字
        foreach ($shieldingword as $shielding) {
            $content = str_ireplace($shielding, '{Censored Word}', $content);
        }
        return $content;
    }

    public function my_custom_admin_head()
    {
        echo '<style>body {background-color: #4AAF48 !important;}</style>';
    }

    public function plugin_activation_cretable()
    {
        global $wpdb;
        $charset_collate = '';
        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }
        $sql = "
            CREATE TABLE " . self::$myNewTable . " (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                name tinytext NOT NULL,
                text text NOT NULL,
                url varchar(55) DEFAULT '' NOT NULL,
                UNIQUE KEY id (id)
            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('my_plugin_version_num', self::MY_PLUGIN_VERSION_NUM);
    }

    public function plugin_activation_insertdate()
    {
        global $wpdb;

        $data['name'] = '我的博客';
        $data['text'] = '欢迎来到我的博客！';
        $data['url'] = 'http://www.cnblogs.com/fxmbz';

        $wpdb->insert(self::$myNewTable, $data);
    }

    function myplugin_update_db_check()
    {
        // 获取到options表里的插件版本号 不等于 当前插件版本号时，运行创建表方法，更新数据库表
        if (get_option('my_plugin_version_num') != self::MY_PLUGIN_VERSION_NUM) {
            $this->plugin_activation_cretable();
        }
    }

    public function plugin_deactivation_deltable()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS " . self::$myNewTable);
        delete_option('my_plugin_version_num');
    }


    public function baw_create_menu()
    {
        // 创建新的顶级菜单
        add_menu_page('BAW Plugin Settings', 'BAW Settings', 'administrator', __FILE__, [$this, 'baw_settings_page'], '');
        // 调用注册设置函数
        add_action('admin_init', [$this, 'register_mysettings']);
    }

    public function register_mysettings()
    {
        // 注册我们的设置
        register_setting('baw-settings-group', 'new_option_name');
        register_setting('baw-settings-group', 'some_other_option');
        register_setting('baw-settings-group', 'option_etc');
    }

    public function baw_settings_page()
    {
        echo '<div class="wrap"><h2>你的插件名称</h2><form method="post" action="options.php">';

        settings_fields('baw-settings-group');
        do_settings_sections('baw-settings-group');
        echo '<table class="form-table">
                <tr valign="top">
                    <th scope="row">New Option Name</th>
                    <td><input type="text" name="new_option_name"
                               value="';
        esc_attr(get_option('new_option_name'));
        echo '"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Some Other Option</th>
                    <td><input type="text" name="some_other_option"
                               value="';
        esc_attr(get_option('some_other_option'));
        echo
        '"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Options, Etc.</th>
                    <td><input type="text" name="option_etc" value="';
        esc_attr(get_option('option_etc'));
        echo '"/>
                    </td>
                </tr>
            </table>';
        submit_button();
        echo '</form></div>';

    }

// 4, 在WordPress后台评论添加一个子菜单
    public function comments_submenu()
    {
        add_comments_page(__('数据保存'), __('数据保存'), 'administrator', 'my-unique-identifier-datasave', [$this, 'add_comments_submenu']);
    }

    public function add_comments_submenu()
    {
        if (isset($_POST['test_hidden']) && $_POST['test_hidden'] == 'y') {
            update_option('test_input_c', $_POST['test_insert_options']); //更新你添加的数据库
            echo '<div id="message" style="background-color: green; color: #ffffff;">保存成功 !</div>';
        }
        $screen_icon = @get_screen_icon(); //显示图标
        $test_input_c = esc_attr(get_option('test_input_c'));
        $html = <<<EOD
<div>
    $screen_icon
    < h2>添加数据 </h2 >

    <form action = "" method = "post" id = "my_plugin_test_form" >
        <h3 >
            <label for="test_insert_options" > 输入测试数据:</label >
            <input type = "text" id = "test_insert_options" name = "test_insert_options" value = "$test_input_c" />
        </h3 >

        <p >
            <input type = "submit" name = "submit" value = "保存" class="button button-primary" />
            <input type = "hidden" name = "test_hidden" value = "y" />
        </p >
    </form >
</div >
EOD;

        echo $html;

    }

// 3, 在WordPress后台添加一个同级主菜单，在主菜单下添加子菜单[注: 需要your-admin-sub-menu2页面存在]
    public function add_settings_menu()
    {
        add_menu_page('自定义菜单标题', '测试菜单', 'administrator', __FILE__, [$this, 'my_function_menu'], false, 100);
        add_submenu_page(__FILE__, '子菜单1', '测试子菜单1', 'administrator', 'your-admin-sub-menu1', [$this, 'my_function_submenu1']);
        add_submenu_page(__FILE__, '子菜单2', '测试子菜单2', 'administrator', 'your-admin-sub-menu2', [$this, 'my_function_submenu2']);
        add_submenu_page(__FILE__, '子菜单3', '测试子菜单3', 'administrator', 'your-admin-sub-menu3', [$this, 'my_function_submenu3']);
    }

    public function my_function_menu()
    {
        echo "<h2>测试菜单设置</h2>";
    }

    public function my_function_submenu1()
    {
        echo "<h2>测试子菜单设置一</h2>";
    }

    public function my_function_submenu2()
    {
        echo "<h2>测试子菜单设置二</h2>";
    }

    public function my_function_submenu3()
    {
        echo "<h2>测试子菜单设置三</h2>";
    }

// 2, 添加版权信息(这是一款简单的插件样例，将自己定义好的内容显示每篇文章后面)
    public function add_copyright_info($content)
    {
        $content .= '
<div style="clear:both; border-top:1px dashed #e0e0e0; padding:10px 0 10px 0; font-size:12px;">
    版权所有©转载必须以链接形式注明作者和原始出处：<a href="' . get_bloginfo(" Url") . '" title="点击去首页">' . get_bloginfo("name") . '</a> » <a
        title="本文地址" href="' . get_permalink() . '">' . get_the_title() . '</a></div>';
        return $content;
    }

// 1, 在wp后台头部输出自定义的字符串
    public function my_first_plugin()
    {
        echo '<h1>我制作的第一个WP插件</h1>';
    }
}


(new smzdm_first())->init();


// [注册小工具] 创建小工具(继承WP_Widget类即可)

class sishen_widget_info extends WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'sishen_widget_info',
            'description' => '显示死神的个人信息',
        );
        parent::__construct('显示个人信息', '死神的小工具', $widget_ops);
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $before_widget = '';
        $after_widget = '';
        extract($args);

        $title = $instance['title'];
        $xingming = $instance['xingming'];
        $book = $instance['book'];

        echo $before_widget;
        echo '<p> 标题: ' . $title . '</p>';
        echo '<p> 姓名: ' . $xingming . '</p>';
        echo '<p> 著作: ' . $book . '</p>';
        echo $after_widget;
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        $defaults = array('title' => '死神的个人信息', 'xingming' => '死神', 'book' => '《SEO的道与术》、《跟死神学wordpress主题开发》');
        $instance = wp_parse_args((array)$instance, $defaults);

        $title = $instance['title'];
        $xingming = $instance['xingming'];
        $book = $instance['book'];
        ?>
        <p>标题: <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text"
                      value="<?php echo esc_attr($title); ?>"/></p>
        <p>姓名: <input class="widefat" name="<?php echo $this->get_field_name('xingming'); ?> " type="text"
                      value="<?php echo esc_attr($xingming); ?> "/></p>
        <p>著作: <textarea class="widefat"
                         name=" <?php echo $this->get_field_name('book'); ?> "/><?php echo esc_attr($book); ?></textarea>
        </p>
        <?php
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags(trim($new_instance['title']));
        $instance['xingming'] = strip_tags(trim($new_instance['xingming']));
        $instance['book'] = strip_tags(trim($new_instance['book']));

        return $instance;
    }
}

