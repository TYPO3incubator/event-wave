import '@typo3-incubator/surfcamp-events/Libs/json-editor.js'
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import Notification from "@typo3/backend/notification.js";

class EventsManagementModule {
    init() {
        if (document.getElementById('appointments_generator') !== null) {
            const element = document.getElementById('appointments_generator');
            new AjaxRequest(TYPO3.settings.ajaxUrls.surfcamp_events_appointments_generate_schema).post({event: element.dataset.eventUid ?? null}, {
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                }
            }).then(async function (response) {
                const schemaFromBackend = await response.resolve();
                const jseditor = new window.JSONEditor(element, {
                    theme: 'bootstrap5',
                    disable_collapse: true,
                    disable_edit_json: true,
                    disable_properties: true,
                    no_additional_properties: true,
                    compact: false,
                    disable_array_delete_last_row: true,
                    array_controls_top: false,
                    disable_array_reorder: true,
                    object_layout: 'normal',
                    schema: schemaFromBackend,
                });
                jseditor.on('ready',() => {
                    const eventUid = element.dataset.eventUid ?? null
                    const submitButton = document.getElementById('submit-button');
                    submitButton?.addEventListener('click', (event) => {
                        event.preventDefault();
                        console.log('Submit!');
                        if (jseditor.validate().length > 0) {
                            Notification.warning('Warning', 'Please fix the validation errors before submitting the Appointment Generation form.');
                        } else {
                            new AjaxRequest(TYPO3.settings.ajaxUrls.surfcamp_events_appointments_generate).post({event: eventUid, data: jseditor.getValue()}, {
                                headers: {
                                    'Content-Type': 'application/json; charset=utf-8'
                                }
                            }).then(async function (response) {
                                const responseText = await response.resolve();
                                console.log(responseText);
                                jseditor.destroy();
                            });
                        }
                    });
                });




            });
        }
    }
}

export default EventsManagementModule;
new EventsManagementModule().init();
