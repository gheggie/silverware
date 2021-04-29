<% if $HasImage %>
  <div class="image">
    <% if $LinkImage %><a href="$Image.URL" title="$Component.Title" class="$ImageLinkClass" data-caption-title="$Component.Title"<% if $ShowCaption %> data-caption-desc="$ImageCaption"<% end_if %>><% end_if %>
    <% with $ImageResized %>
      <img src="$URL" alt="$Title">
    <% end_with %>
    <% if $LinkImage %></a><% end_if %>
  </div>
<% end_if %>
<% if $ShowCaption %>
  <div class="caption captionImage">
    <p>$ImageCaption</p>
  </div>
<% end_if %>