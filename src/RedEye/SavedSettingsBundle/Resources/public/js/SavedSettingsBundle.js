"use strict";
(function ($) {
    $.fn.savedSearchSettings = function() {
        var savedSearches;

        function loadSavedSearches() {
            var searchSelect = $('#savedSearches');
            searchSelect.prop('disabled', true);
            $.ajax({
                url: '/sf2test/web/app_dev.php/settings/read',
                type: 'GET',
                dataType: 'JSON'
            }).done(function(data) {
                // store the response
                savedSearches = data.savedSearches;
                // clear any existing options
                searchSelect.find('option').remove();
                // populate select with options
                savedSearches.forEach(function(search) {
                    searchSelect.append('<option>' + search.searchDesc + '</option>');
                });
                $('#statusText').html('Loaded saved searches');
                $('#savedSearches').prop('disabled', false);
            });
        }

        // loadSavedSearches on document.ready;
        loadSavedSearches();

        // event handler for search option selection
        $('#savedSearches').on('change', function(e) {
            var selectedSearch = $(this).val();
            $('#statusText').html('Loaded saved search: ' + selectedSearch);
            if(savedSearches.length > 0) {
                savedSearches.forEach(function(search) {
                    if (search.searchDesc === selectedSearch) {
                        $('#searchText').val(search.searchText);
                        var searchCriteria = [];
                        search.searchCriteria.split(',').forEach(function(criteria) {
                            searchCriteria.push(criteria.split(':'));
                        });
                        searchCriteria.forEach(function(criteria) {
                            $('#criteria' + criteria[0]).val(criteria[1]);
                        });
                    }
                });
            }
        });


        // event handler for saving searches
        $('#saveSearch').on('click', function(e) {
            e.preventDefault();
            var searchText, searchDesc, searchCriteria = [];
            $('#searchForm :input').each(function(idx, element) {
                var id = element.id;
                var criteriaRegEx = new RegExp('criteria[0-9]');
                if(criteriaRegEx.test(id)) {
                    searchCriteria.push([id.slice(-1), element.value]);
                }
                if (id === 'searchText') {
                    searchText = element.value;
                }
            });

            if (searchText && searchCriteria) {
                // would probably use a modal instead of a prompt box
                searchDesc = prompt('Name your search');
                var searchJson = { searchText: searchText, searchCriteria: searchCriteria, searchDesc: searchDesc};
                console.log('Sending: ' + JSON.stringify(searchJson));
                $.ajax({
                    url: '/sf2test/web/app_dev.php/settings/create',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json; charset=utf-8',
                    data: JSON.stringify(searchJson)
                }).done(function(data) {
                    console.log(data);
                    $('#statusText').html('Saved search');
                    // after saving a new search repopulate options
                    loadSavedSearches();
                });
            }
        });

        $('#submitSearch').on('click', function(e) {
            e.preventDefault();
            $('#statusText').html('SEARCHING...');
        });
    }
})(window.jQuery);