<?php
/*
FormForAll :: Common functions
*/

function formforall_install() { }

function formforall_remove() {
    delete_option('formforall'); 
}

/*******************************************************/
/* GENERAL DISPLAY */
/*******************************************************/
/**
 * Displays the iframe with the form
 */
function formforall_front_display($formid, $timestamp) {
    $url = 'www.formforall.com';
    $buffer = '<script src="https://' . $url . '/assets/javascripts/ffa.js" type="text/javascript"></script>';
    $buffer .= '<div id="formforall_container' . $timestamp . '"></div>
		<script type="text/javascript">
		 var ffa = new __FFA(
		   document.getElementById("formforall_container' . $timestamp . '"),
		   "' . $formid . '",
		   "' . $url . '"
		 );
		</script>';
    return $buffer;
}


/*******************************************************/
/* WIDGET */
/*******************************************************/
add_action( 'widgets_init', 'formforall_add_widget');

/**
 * Adds the widget
 */
function formforall_add_widget() {
    register_widget('FormForAll_Widget');
}

/**
 * Class which manages the widget
 */
class FormForAll_Widget extends WP_Widget {
    /**
    * Register widget with WordPress.
    */
   function __construct() {
	parent::__construct(
	    'formforall_widget', // Base ID
	    'FormForAll', // Name
	    array('description' => __("Easily embedded forms", "formforall")) // Args
	);
   }
    
    /**
    * Back-end widget form.
    * @param array $instance Previously saved values from database.
    */
   public function form( $instance ) {
	if (isset($instance['formid'])) {
	    $formid = $instance['formid'];
	}
	
	$option = get_option('formforall');
	if (!isset($option['user_id'])) $option['user_id'] = '';
	if (!isset($option['api_key'])) $option['api_key'] = '';
	
	$opts = array (
	    'http' => array (
		'method' => "GET",
		'header' => "Authorization: " . $option['api_key']
	    )
	);
	$context = stream_context_create($opts);
	
	$requestUrlPath = "https://www.formforall.com/api/users/" . $option['user_id'] . "/forms";
	$file = @file_get_contents($requestUrlPath, false, $context);
	if ($file === FALSE) {
	    ?><p><a href="options-general.php?page=formforall-settings"><?php echo __("Parameters have not been set correctly", 'formforall'); ?></a></span>
	    <?php 
	} else {
	    $result = json_decode($file);

	    ?>
	    <p>
	    <label for="<?php echo $this->get_field_name('formid'); ?>"><?php echo __("Form", 'formforall'); ?></label> 
	    <select name="<?php echo $this->get_field_name('formid'); ?>" id="<?php echo $this->get_field_id('formid'); ?>">
		<?php 
		    for ($i = 0; $i < count($result); $i++) echo '<option value="' . $result[$i]->id .'"'. ($result[$i]->id == $formid ? 'selected="selected"' : '') .'>' . $result[$i]->title . '</option>';
		?>
	    </select>
	    </p>
	    <?php
	}
   }
   
   /**
    * Sanitize widget form values as they are saved.
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
   public function update($new_instance, $old_instance) {
	$instance = array();
	$instance['formid'] = (!empty( $new_instance['formid'])) ? strip_tags($new_instance['formid']) : '';
	return $instance;
   }
   
   /**
    * Front-end display of widget.
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance ) {
	$formid = apply_filters( 'widget_title', $instance['formid'] );

	echo $args['before_widget'];
	
	echo formforall_front_display($formid, $this->get_field_id('formid'));
	
	echo $args['after_widget'];
    }
}
?>
