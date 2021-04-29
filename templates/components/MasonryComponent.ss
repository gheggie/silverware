<% if $ListItems %>
  <div id="$WrapperID" class="masonry-grid" data-squery="min-width:750px=wide">
    <div class="masonry-grid-sizer"></div>
    <% loop $ListItems %>
      <div class="masonry-grid-item">
        <a href="$MetaImageLink($Up.ImageLinksTo)" rel="$Up.HTMLID" title="$MetaTitle" class="$Up.ImageLinkClass" data-caption-title="$MetaTitle" data-caption-desc="$MetaImageCaption">
          <% with $MetaImageResized($Up.ImageWidth, $Up.ImageHeight, $Up.ImageResize) %>
            <img src="$URL" alt="$Title">
          <% end_with %>
        </a>
      </div>
    <% end_loop %>
  </div>
<% else %>
  <div class="error">
    <p><i class="fa fa-fw fa-warning"></i> <% _t('MasonryComponent_ss.NODATAAVAILABLE', 'No data available.') %></p>
  </div>
<% end_if %>