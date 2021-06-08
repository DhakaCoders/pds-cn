<?php
$current_category = get_queried_object();

if($current_category->parent > 0){
    $parent_term_id = $current_category->parent;
    $parent_term = get_term( $parent_term_id, $taxonomy );
    $categories = get_terms( array(
        'taxonomy' => PRODUCT_CATEGORY,
        'hide_empty' => false,
        'parent'    => $current_category->parent
    ) );
}else{
    $parent_term_id = $current_category->term_id;
    $categories = get_terms( array(
        'taxonomy' => PRODUCT_CATEGORY,
        'hide_empty' => false,
        'parent'    => $current_category->term_id
    ) );
}

if(count($categories) > 0){
    ?>
    <div class="list-categories">
        <div class="list-categories-content">
            <ul class="list-categories-items">
                <li class="list-category-item">
                    <a href="<?php echo get_term_link($parent_term_id, PRODUCT_CATEGORY); ?>"
                       title="<?php echo __('Alle', 'paul'); ?>"
                       class="<?php if($parent_term_id == $current_category->term_id) echo 'active'; ?>"><?php echo __('Alle', 'paul'); ?></a>
                </li>
                <?php
                $current_category_id = $current_category->term_id;
                foreach( $categories as $category ) {
                    $check = false;
                    if($category->term_id == $current_category_id){
                        $check = true;
                    }
                    ?>
                    <li class="list-category-item">
                        <a href="<?php echo esc_url(get_term_link( $category->term_id, PRODUCT_CATEGORY )); ?>"
                           title="<?php echo $category->name; ?>"
                           class="<?php if($check) echo 'active'; ?>"><?php echo esc_html( $category->name ); ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <div class="mobile-select">
                <select class="wide nice-select list-categories-items" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option class="list-category-item" value="<?php echo get_term_link($parent_term_id, PRODUCT_CATEGORY); ?>" <?php if($parent_term_id == $current_category->term_id) echo 'selected'; ?>>
                        <?php echo __('Alle', 'paul'); ?>
                    </option>
                    <?php
                    foreach( $categories as $category ) {
                        $selected = '';
                        if($category->term_id == $current_category_id){
                            $selected = 'selected';
                        }
                        ?>
                        <option class="list-category-item" value="<?php echo get_term_link($category->term_id, PRODUCT_CATEGORY); ?>" <?php if($selected) echo 'selected' ?>>
                            <?php echo $category->name; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <?php
}