$(function() {
    //var $previewContainer = $('#comment-md-preview-container');
    //$previewContainer.hide();
    var $md = $("#content").markdown({
        language: "zh",
        autofocus: true,
        iconlibrary: 'fa',
        resize: "both",
        onShow: function(e) {
            //e.hideButtons('cmdPreview');
            e.change(e);
        },
        onChange: function(e) {
            var content = e.parseContent();
            //if (content === '') $previewContainer.hide();
            //else $previewContainer.show().find('#comment-md-preview').html(content).find('table').addClass('table table-bordered table-striped table-hover');
        },
        footer: function(e) {
            return '\
					<span class="text-muted">\
						<span data-md-footer-message="err">\
						</span>\
						<span data-md-footer-message="default">\
								<input type="file" multiple="" name="upload_file" id="comment-images" />\
						</span>\
						<span data-md-footer-message="loading">\
							上传中...\
						</span>\
					</span>';
        }
    });

    var $mdEditor = $('.md-editor'),
        msgs = {};

    $mdEditor.find('[data-md-footer-message]').each(function() {
        msgs[$(this).data('md-footer-message')] = $(this).hide();
    });
    msgs.
        default.show();
    $mdEditor.filedrop({
        binded_input: $('#comment-images'),
        url: "/upimg",
        fallbackClick: false,
        beforeSend: function(file, i, done) {
            msgs.default.hide();
            msgs.err.hide();
            msgs.loading.show();
            done();
        },
        //maxfilesize: 15,
        error: function(err, file) {
            var errstr="";
            switch (err) {
                case '浏览器不支持':
                    errstr='浏览器不支持HTML5';
                    break;
                case 'FileExtensionNotAllowed':
                    // The file extension is not in the specified list 'allowedfileextensions'
                    errstr='只支持上传jpg,jpeg,.png,gif格式图片';
                    break;
                case 'TooManyFiles':
                    // user uploaded more than 'maxfiles'
                    errstr='最多能上传25张图片';
                    break;
                case 'FileTooLarge':
                    errstr='最多每张图片只能有2MB';
                    break;
                case 'FileTypeNotAllowed':
                    errstr='只支持上传jpg,jpeg,.png,gif格式图片';
                    break;
                default:
                    break;
            }
            var filename = typeof file !== 'undefined' ? file.name : '';
            msgs.loading.hide();
            msgs.err.show().html('<span class="text-danger"><i class="fa fa-times"></i> 上传错误 ' + filename + ' - ' + errstr + '</span><br />');
        },
        allowedfiletypes: ['image/jpeg','image/png','image/gif'],   // filetypes allowed by Content-Type.  Empty array means no restrictions
        allowedfileextensions: ['.jpg','.jpeg','.png','.gif'],
        maxfiles: 25,
        maxfilesize: 2,    // max file size in MBs
        dragOver: function() {
            $(this).addClass('active');
        },
        dragLeave: function() {
            $(this).removeClass('active');
        },
        progressUpdated: function(i, file, progress) {
            msgs.loading.html('<i class="fa fa-refresh fa-spin"></i> 上传中 <span class="text-info">' + file.name + '</span>... ' + progress + '%');
        },
        afterAll: function() {
            msgs.default.show();
            msgs.loading.hide();
            msgs.err.hide();
        },
        uploadFinished: function(i, file, response, time) {
            $md.val($md.val() + "![" + file.name + "]("+response+")\n").trigger('change');
        }
    });
})