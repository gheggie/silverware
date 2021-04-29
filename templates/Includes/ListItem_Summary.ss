<% if $HasMetaSummary %>
  <% if $Component.SummaryShown($IsFirst) %>
    <div class="summary">
      <p>$MetaSummary</p>
    </div>
  <% end_if %>
<% end_if %>