
// import {FormsettingHeader} from './settings/form_setting_header';
import {FormField} from './settings/form_fields';
// import {SaveFormSettings} from './settings/form_setting_header';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
const { __ } = wp.i18n;

export const FormSettings = (props) => {


    const [FieldItems , setFielditems] = wp.element.useState(props.data.fields);
    const [Title, setTitle] = wp.element.useState(props.data.title);
    const [url, setUrl] = wp.element.useState(props.data.redirection_url);
    const [msg, setSuccessMsg] = wp.element.useState(props.data.success_message);
    const [showForm, SetShowFormToggle] = wp.element.useState('false' == props.data.show_form ? false :true);

    let dragSource = null;
    let dropTarget = null;

    const dragStart = (e, position) => {
        dragSource =position;
    };

      const dragEnter = (e, position) => {
        dropTarget = position;
      };

      const drop = (e, position) => {
        const copyFieldItems = [...FieldItems];

        const [dragItemContent] = copyFieldItems.splice(position, 1);
        dragItemContent.is_editing = false;
        copyFieldItems.splice(dropTarget, 0, dragItemContent); 

       
        setFielditems([...copyFieldItems]);

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: 'elex_raq_rearrange_fields',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
              source_index: position,
              destination_index: dropTarget,
            },
            success: function(data) {
                window.location.reload();
            }
          })


      };

      const reArrangeApi = (sourceIndex,destinationIndex) => {

      }

    const onClickEdit = () => {

        SetSaveFieldClasses("save_field btn btn-sm btn-success rounded-circle");
        SetEditFieldClasses("d-none edit_field btn btn-sm btn-white rounded-circle elex-ppct-btn-purple-hover");

      }

    const AddNewField = () => {
        
        const newFormFields = [...FieldItems];
        newFormFields.push({
            name: '',
            type: 'text',
            placeholder: '',
            connected_to:'',
            status:'',
            mandatory: true,
            deletable: true,
            is_editing:true,
            is_new_field:true,
            is_radio_checkbox:false,
            options:[]
            ,

        });
        setFielditems(newFormFields);

    

    }

    const onFormFieldRowRemove = (i) => {
    
        const newFormFields = [...FieldItems];
       

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: 'req_frm_delete_field',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
              key: newFormFields[i].key,
            },
            success: function(data) {

                if(data.data.code === 200){
                    jQuery("#elex-raq-deleted").addClass("show");
                }
                
                setTimeout(function() {

                    jQuery("#elex-raq-deleted").removeClass("show");

                }, 3000);
             
        
            }
          })

          newFormFields.splice(i,1);
          setFielditems(newFormFields);

    
    }
    const UpdateField = (field,i) => {
       
         let newFormFields = [...FieldItems];
        newFormFields[i] = field;
        setFielditems(newFormFields);
       

    }
    const UpdateToggle = (field,i) => {
       
        let newFormFields = [...FieldItems];
       newFormFields[i] = field;
       setFielditems(newFormFields);

       jQuery.ajax({
        type: "post",
        url: raq_formsetting_ajax_object.ajax_url,
        data: {
          action: 'req_frm_toggle_field',
          ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
          key: newFormFields[i].key,
        },
        success: function(data) {
            if(data.data.code === 200){
                jQuery("#elex-raq-updated-sucess-toast").addClass("show");
            }
            
            setTimeout(function() {
                jQuery("#elex-raq-updated-sucess-toast").removeClass("show");

            }, 3000);
            
    
        }
      })


   }
   
    const SaveFormSettings = (e) => {
        e.preventDefault();

        let newFormFields = [...FieldItems];

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: 'elex_raq_save_form_settings_data',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
                title: Title,
                show_form:showForm,
                redirection_url :url,
                success_message:msg,
                form_fields:newFormFields
 
            },
            success: function(data) {
                if(data.data.code === 1){
                    jQuery("#elex-raq-saved-sucess-toast").addClass("show");
                }
                setTimeout(function() {

                    jQuery("#elex-raq-saved-sucess-toast").removeClass("show");

                }, 3000);
                window.location.reload();

            }
          })
    
    }

   
    const CheckBox = () => {
        if(false === showForm ){
            return(
                <input name="show_form"  onChange={(e) =>  {SetShowFormToggle(e.target.checked)}}  value={showForm} type="checkbox" />

            )
        }
        return(
            <input name="show_form" checked onChange={(e) =>  {SetShowFormToggle(e.target.checked)}}  value={showForm} type="checkbox" />

        )
    }
    return  (<><form method="POST" ><div className="pt-3">
        <h5 className="fw-bold">{__('Form Settings','elex_request_a_quote_premium')}</h5>
    </div><div className="p-3">
            <div className="row">
                <div className="col-12">


                    <div class="row align-items-center mb-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{__('Show Request a Quote Form','elex_request_a_quote_premium')}</h6>

                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <label class="elex-switch-btn">
                                <CheckBox />
                                <div class="elex-switch-icon round"></div>
                            </label>

                            <div>
                                <small class="text-secondary">{__('Turn this Off, if you want to use any third party form to get the quote requests.','elex_request_a_quote_premium')}
                                    
                                </small>
                            </div>
                        </div>
                    </div>
                    <div className="row align-items-center mb-3">
                        <div className="col-lg-4 col-md-6">
                            <div className="d-flex justify-content-between align-items-center">
                                <h6 className="mb-0">{__('Form Header Title','elex_request_a_quote_premium')}</h6>
                                <div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter the text you want to display as the header/title of the quote form.">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                    <g id="tt" transform="translate(-384 -226)">
                                        <g id="Ellipse_1" data-name="Ellipse 1"
                                            transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
                                            stroke-width="1">
                                            <circle cx="13" cy="13" r="13" stroke="none" />
                                            <circle cx="13" cy="13" r="12.5" fill="none" />
                                        </g>
                                        <text id="_" data-name="?" transform="translate(392 247)"
                                            font-size="20" font-family="Roboto-Bold, Roboto"
                                            font-weight="700">
                                            <tspan x="0" y="0">?</tspan>
                                        </text>
                                    </g>
                                </svg>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6">
                            <input name="title" onChange={(e) => setTitle(e.target.value)} type="text" value={Title} className="form-control" placeholder={__('Fill Your Details' , 'elex_request_a_quote_premium')} />
                        </div>
                    </div>

                    <h5 className="fw-bold mb-3">{__('Form Submit Actions','elex_request_a_quote_premium')}</h5>

                    <div className="row align-items-center mb-3">
                        <div className="col-lg-4 col-md-6">
                            <div className="d-flex justify-content-between align-items-center">
                                <h6 className="mb-0">{__('"Send Request" Button Redirectional URl','elex_request_a_quote_premium')}</h6>
                                <div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Please provide the page URL that you wish to redirect to, After clicking the 'Send Request' button">
                                
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                    <g id="tt" transform="translate(-384 -226)">
                                        <g id="Ellipse_1" data-name="Ellipse 1"
                                            transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
                                            stroke-width="1">
                                            <circle cx="13" cy="13" r="13" stroke="none" />
                                            <circle cx="13" cy="13" r="12.5" fill="none" />
                                        </g>
                                        <text id="_" data-name="?" transform="translate(392 247)"
                                            font-size="20" font-family="Roboto-Bold, Roboto"
                                            font-weight="700">
                                            <tspan x="0" y="0">?</tspan>
                                        </text>
                                    </g>
                                </svg>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6">
                            <input type="text" name="redirection_url" onChange={(e) => setUrl(e.target.value)} value={url} className="form-control" placeholder="https://example.com/sample" />
                        </div>
                    </div>

                    <div className="row align-items-center mb-3">
                        <div className="col-lg-4 col-md-6">
                            <div className="d-flex justify-content-between align-items-center">
                                <h6 className="mb-0">{__('"Send Request" Success Message','elex_request_a_quote_premium')}</h6>
                                <div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter the text that you want to display as a message after the successful submission of the quote request.">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                    <g id="tt" transform="translate(-384 -226)">
                                        <g id="Ellipse_1" data-name="Ellipse 1"
                                            transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
                                            stroke-width="1">
                                            <circle cx="13" cy="13" r="13" stroke="none" />
                                            <circle cx="13" cy="13" r="12.5" fill="none" />
                                        </g>
                                        <text id="_" data-name="?" transform="translate(392 247)"
                                            font-size="20" font-family="Roboto-Bold, Roboto"
                                            font-weight="700">
                                            <tspan x="0" y="0">?</tspan>
                                        </text>
                                    </g>
                                </svg>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6">
                            <input type="text" name="success_message" onChange={(e) => setSuccessMsg(e.target.value)} value={msg} className="form-control" placeholder={__('Your request has been sent successfully.','elex_request_a_quote_premium')} />
                        </div>
                    </div>
                </div>
            </div>
        </div><h5 className="fw-bold">
            {__('Form Field Settings','elex_request_a_quote_premium')}
        </h5><div className="p-3">
            <div className="row">
                <div className="col-12">

                    <div className="bg-warning bg-opacity-10 p-2  mb-3 elex-ppct-warning">
                        <small>{__("When you create custom fields, the placeholder for the email template will be created based on the label text (For eg: Upload Image will be @Upload_Image). If you make changes to the label text in the future, please make sure to use the updated placeholder in the email body. Also, the label text cannot be modified for orders placed before the change.",'elex_request_a_quote_premium')}</small>
                    </div>
                    <div className="border rounded-3  mb-3">
                   
                            <table  id="table" className="table mb-0 align-middle" style={{ tableLayout: "fixed" }}>
                                <thead>
                                <tr className="table-light">
                                    <th width="30px"></th>
                                    <th scope="col-3">{__('Label Name','elex_request_a_quote_premium')}</th>
                                    <th scope="col-3">{__('Field Type ','elex_request_a_quote_premium')} </th>
                                    <th scope="col-3">{__('Placeholder ','elex_request_a_quote_premium')}</th>
                                    <th scope="col-2">{__('Connected To ','elex_request_a_quote_premium')}</th>
                                    <th scope="col-2" width="80px">{__('Required ','elex_request_a_quote_premium')}</th>
                                    <th scope="col-1" width="90px">{__('Actions ','elex_request_a_quote_premium')}</th>
                                </tr>
                                </thead>

                            <tbody className="border-top-0 form_field_container">
                                {FieldItems.map((field, i) => {
                                    return <FormField 
                                        key={i} 
                                        onDragEnd={e => drop(e, i)} 
                                        onDragEnter={(e) => dragEnter(e, i)} 
                                        onDragStart={(e) => dragStart(e, i)} 
                                        onEdit={onClickEdit}   
                                        data={field}
                                        // chipvalues = {(field.options[0]) ? field.options[0].label : []}
                                        onChange={field => UpdateField(field, i)} 
                                        onUpdateToggle={field => UpdateToggle(field, i)} 
                                        
                                        onDelete={() => onFormFieldRowRemove(i)}  
                                    />
                                })}
                            </tbody>

                        </table>

                        <button onClick={(e) => AddNewField() } type="button" className="add_new_field btn bg-primary bg-opacity-10 text-primary border border-primary m-2">
                        {__('Add New Field ','elex_request_a_quote_premium')}</button>
                    </div>
                    <input type="hidden" name="elex_form_settings_nonce" value={raq_formsetting_ajax_object.nonce} />
                    <div className="m-3">
                        <button  name="submit" type="submit" onClick={SaveFormSettings}  className=" btn btn-primary">{__('Save Changes','elex_request_a_quote_premium')}</button>
                    </div>
                </div>
            </div>
        </div>
        
        </form>
        </>)

}

jQuery(document).ready(function () {
    
    let form_settings = JSON.parse(window.form_settings);

    form_settings.fields = form_settings.fields.map(field => {
        field.id = Math.random();
        return field;   
    });
   
    wp.element.render(
            <FormSettings data={form_settings} key={form_settings.title} />,
            document.getElementById('form_settings')
        );  
});