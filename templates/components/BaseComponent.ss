<div $AttributesHTML>
  <% if $ShowTitle %>
    <header>
      <h3<% if $TitleClass %> class="$TitleClass"<% end_if %>><span>$Title</span></h3>
    </header>
  <% end_if %>
  <div class="content">
    $Content
    <% if $Form %>
      <div class="form">
        $Form
      </div>
    <% end_if %>
  </div>
</div>