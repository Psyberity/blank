<div class="form-group">
    {{ hidden.render() }}
    <label for="{{ field.getName() }}" class="col-sm-2 control-label">{{ field.getLabel() }}</label>
    <div class="col-sm-10">
        <div class="onoffswitch">
            {{ field.render() }}
            <label class="onoffswitch-label" for="{{ field.getAttribute('id') }}">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
            </label>
        </div>
    </div>
</div>