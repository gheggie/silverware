<% loop $DisplayGrid %>
  <% include DisplayRules Display=$Display, MinWidth=$MinWidth, Hidden=$Hidden, Shown=$Shown %>
<% end_loop %>