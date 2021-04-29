<% if $EnabledTabs %>
  <div id="$WrapperID" data-squery="min-width:{$AccordionWidth}px=wide">
    <ul>
      <% loop $EnabledTabs %>
        <li><a href="$Anchor">$FontIconTag<span>$Title</span></a></li>
      <% end_loop %>
    </ul>
    <% loop $EnabledTabs %>
      <div id="$TabID">
        $Content
      </div>
    <% end_loop %>
  </div>
<% end_if %>