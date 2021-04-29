<% if $MinWidth %>@media (min-width: {$MinWidth}px) {<% end_if %>
  <% loop $Hidden %>
    $CSSID { display: none; }
  <% end_loop %>
  <% loop $Shown  %>
    $CSSID { display: block; }
  <% end_loop %>
<% if $MinWidth %>}<% end_if %>