jQuery(function ($) {
  // console.log(SadhguruQuotes.quotes);

  const getCurrentQuoteIndex = function () {
    var quote = $("#sadhguru-quotes--content").attr("quote-index");
    return Number(quote);
  };

  const updateUI = function (index) {
    $("#sadhguru-quotes--content").html(SadhguruQuotes.quotes[index].summary);
    $("#sadhguru-quotes--date").html(SadhguruQuotes.quotes[index].title);
    $("#sadhguru-quotes--content").attr("quote-index", index);

    if (index === 0) {
      // Disable Previous button because we reached the start
      $("#sadhguru-quotes .btn-prev").attr("disabled", true);
    } else {
      // Enable Previous button because we are not at the start
      $("#sadhguru-quotes .btn-prev").attr("disabled", false);
    }
  };

  // Initial State
  updateUI(0);

  // Previous Button
  $("#sadhguru-quotes .btn-prev").on("click", function () {
    updateUI(getCurrentQuoteIndex() - 1);
  });

  // Next Button
  $("#sadhguru-quotes .btn-next").on("click", function () {
    var nextQuoteIndex = getCurrentQuoteIndex() + 1;

    if (nextQuoteIndex < SadhguruQuotes.quotes.length) {
      updateUI(nextQuoteIndex);
    } else {
      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "sadhguru_quotes_next",
          _wpnonce: SadhguruQuotes.nonce,
          next_quote_index: nextQuoteIndex,
        },
        success: function (response) {
          if (response) {
            // Add recently fetched quotes to the array
            SadhguruQuotes.quotes = response.data;
            updateUI(nextQuoteIndex);
            // console.log(response.data);
          }
        },
      });
    }
  });

  // Clear Cache
  $("#sadhguru-quotes .btn-clear").on("click", function () {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "sadhguru_quotes_clear",
        _wpnonce: SadhguruQuotes.nonce,
      },
      success: function (response) {
        if (response) {
          console.log(response.data);
        }
      },
    });
  });
});
