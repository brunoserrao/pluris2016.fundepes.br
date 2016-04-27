<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div>
        <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
        <button type="submit" id="searchsubmit" ><span class="fa-search"></button>
    </div>
</form>