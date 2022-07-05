<?php
namespace Elementor;

class project_card extends Widget_Base {

    public function get_name() {
		return 'project_card';
	}

	public function get_title() {
		return __( 'Project' );
	}

	public function get_icon() {
		return 'eicon-post-list';
    }


   public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_enqueue_style( 'project-card', plugin_dir_url( __DIR__  ) . '../css/tcr/project-card.css','1.1.0');
  }

   public function get_style_depends() {
    //  wp_register_style( 'post-recent-widget', plugin_dir_url( __DIR__  ) . 'css/deskspace/post-recent-widget.css','1.1.0');
     return [ 'project-widget' ];
   }




	protected function _register_controls() {
		$mine = array();
    $categories = get_categories(array(
			'orderby'   => 'name',
			'order'     => 'ASC'
		));

		foreach ($categories as $category ) {
	     $mine[$category->term_id] = $category->name;
		}

			$this->start_controls_section(
				'content_section',
				[
					'label' => __( 'Content', 'post-plus' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

        // Post categories.
		// $this->add_control(
		// 	'category',
		// 	[
        // 'label' => '<i class="fa fa-folder"></i> ' . __( 'Category', 'yp-core' ),
		// 		'type' => \Elementor\Controls_Manager::SELECT2,
		// 		'default' => 'none',
        // 'options'   => $mine,
		// 		'multiple' => false,
		// 	]
		// );

    $this->add_control(
        'per_posts',
        [
          'label' => __( 'Posts Per Page', 'yp-core' ),
          'type' => \Elementor\Controls_Manager::TEXT,
          'default' => __( '5', 'yp-core' ),
          'placeholder' => __( 'เช่น 5', 'yp-core' ),
        ]
      );

      $this->add_control(
          'post_offset',
          [
            'label' => __( 'Offset', 'yp-core' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __( '', 'yp-core' ),
            'placeholder' => __( 'เช่น 1', 'yp-core' ),
          ]
        );
      
        $this->add_control(
          'column',
          [
            'type' => \Elementor\Controls_Manager::SELECT,
            'label' => esc_html__( 'Column', 'plugin-name' ),
            'options' => [
              '1' => esc_html__( '1', 'yp-core' ),
              '2' => esc_html__( '2', 'yp-core' ),
              '3' => esc_html__( '3', 'yp-core' ),
              '4' => esc_html__( '4', 'yp-core' ),
            ],
            'default' => '1',
          ]
        );

        $this->end_controls_section();
		}

	protected function render() {
    $settings = $this->get_settings_for_display();
    $offset = $settings['post_offset'];
    if ($offet == '') {
      $offet = 0;
    }
    $num_posts = $settings['num_posts'];
    if ($num_posts == '') {
        $num_posts = 1;
    }
    // $cat_x = $settings['category'];
    // if ($cat_x == '') {
    //     $cat_x = 0;
    // }
    $column   = $settings['column'];
    switch ($column ) {
      case 1:
        $num_column = 1;
        $num_column_tablet = 1;
        $num_column_mobile = 1;
        $c_class = 'c_1_class';
      break;
      case 2:
        $num_column = 2;
        $num_column_tablet = 2;
        $num_column_mobile = 1;
        $c_class = 'c_2_class';
      break;
      case 3:
        $num_column = 3;
        $num_column_tablet = 2;
        $num_column_mobile = 1;
        $c_class = 'c_3_class';
      break;      
      default:
        $num_column = 4;
        $num_column_tablet = 2;
        $num_column_mobile = 1;
        $c_class = 'c_4_class';
        break;
    }
    ?>
    <?php
    $args = array(
    'post_type' => array( 'project'),
    // 'tax_query'         => array(
    //        array(
    //            'taxonomy'  => 'category',
    //            'field'     => 'term_id',
    //            'terms'     => $cat_x
    //        )
    //      ),
    'posts_per_page'  => $settings['per_posts'],
    'offset'    => $offset,
    'orderby'    => 'date',
    'order'    => 'DESC'
    );
    query_posts( $args );
    ?>


    <div class="vc_posts card style-1 v2 project_grid">
        <div class="vc_posts-wrapper <?php echo $c_class; ?>" style="grid-template-columns: repeat(<?php echo $num_column; ?>, 1fr);">
      <?php if ( have_posts()) : ?>
        <?php while ( have_posts() ) : the_post(); 
            $location = get_field('location');
            $start_price = get_field('start_price');
            $option_mrt = get_field('option_mrt');
            $option_street = get_field('option_street');
            $option_vacation = get_field('option_vacation');           
        ?>
          <?php $term = get_the_terms(get_the_ID(), 'project_catgory'); ?>

                <article class="vcps-item">
                  <div class="featured-croped">
                    <a href="<?php the_permalink(); ?>">
                      <div class="in-croped">
                        <div class="divide-obj"></div>
                    <?php if(has_post_thumbnail()) { the_post_thumbnail();} else { echo '<img src="' . esc_url( get_stylesheet_directory_uri()) .'/img/thumb.png" alt="'. get_the_title() .'" />'; }?>
                      </div>
                    </a>
                  </div>

                  <div class="vcps-info">
                      <div class="term-box">
                      <ul class="nav-sub-term-yp">
                            <?php
                            if( $term ):
                            $i = 0;
                            foreach ( $term as $term_id ) {
                            $i++;
                            $slug = $term_id->slug;
                            // if($slug == 'uncategorized'){ continue; }
                                if($i <= 3):
                                ?>
                                <li class="<?php echo $term_id->slug; ?>"><?php echo $term_id->name; ?></li>
                                <?php
                                endif;
                            } 
                        
                        else: 
                            ?>
                            <li class="none-cat"><?php echo esc_html__( 'ไม่มีหมวดหมู่', 'yp-core' ); ?></li>
                            <?php
                        endif;?>
                        </ul>
                      </div>
                      <div class="title-box">
                        <h3 class="vc-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                      </div>
                      <div class="option-bar">
                        <ul class="nav-option">
                        <?php if($location): ?>
                            <li class="list-option"><?php echo $location; ?></li>
                        <?php endif; ?>
                        <?php if($option_mrt == true): ?>
                            <li class="list-option"><?php echo esc_html__( 'ใกล้รถไฟฟ้า', 'yp-core' ); ?></li>
                        <?php endif; ?>
                        <?php if($option_street == true): ?>
                            <li class="list-option"><?php echo esc_html__( 'ติดถนนใหญ่', 'yp-core' ); ?></li>
                        <?php endif; ?>
                        <?php if($option_vacation == true): ?>
                            <li class="list-option"><?php echo esc_html__( 'บ้านพักต่างอากาศ', 'yp-core' ); ?></li>
                        <?php endif; ?>
                        </ul>
                      </div>
                    <div class="p_excerpt">
                      <?php the_excerpt(); ?>
                    </div>

                    <div class="grid-info">
                        <div class="post-meta">
                            <span class="post_date">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <?php echo esc_html__( 'เริ่มต้น', 'yp-core' ); ?><span><?php echo $start_price; ?></span>
                            </span>
                        </div>
                        <a class="vc-view-more" href="<?php echo get_the_permalink(get_the_ID()); ?>">
                            <svg xmlns=" http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo esc_html__( 'ดูรายละเอียด', 'yp-core' ); ?>
                        </a>
                    </div>
                  </div>
                </article>


        <?php endwhile; ?>
        <?php endif; ?>
        <?php wp_reset_query(); ?>


      </div>
    </div>

    <style>
/*ipad pro (large tablet)*/
@media (max-width: 1024px) and (min-width: 992px){
  .project_grid .vc_posts-wrapper {
    grid-template-columns: repeat(<?php echo $num_column_tablet; ?>, 1fr) !important;
}
}
/*ipad (tablet)*/
@media (max-width: 991.98px) {
  .project_grid .vc_posts-wrapper {
    grid-template-columns: repeat(<?php echo $num_column_tablet; ?>, 1fr) !important;
}
  
}
/*iphone8 (smartphone)*/
@media (max-width: 575.98px) {
  .project_grid .vc_posts-wrapper {
    grid-template-columns: repeat(<?php echo $num_column_mobile; ?>, 1fr) !important;
}
}
/*iphone5 (small smartphone)*/
@media (max-width: 360px) {
}
 </style>
		<?php
    }

	protected function _content_template() {}


}
