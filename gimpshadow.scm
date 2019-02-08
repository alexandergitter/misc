; This is based on the script-fu template at
; http://www.home.unix-ag.org/simon/files/script-fu-template.scm

; Lets start the actual script. First define the function that does the
; actual work. Choose a name that does not clash with other names in the
; PDB. It starts with "script-fu" by convention.

; Functions that should be registered in the images context menu have
; to take the image and current drawable as the first two arguments.

(define (script-fu-stylized-shadow image drawable)

	(let* (

		; calls to PDB functions always return a list. We have
		; to pick the first element with "car" explicitely, even
		; when the function called returns just one value.
		(width  (car  (gimp-image-width image)))
		(height (car  (gimp-image-height image)))
		(x0     (car  (gimp-drawable-offsets drawable)))
		(y0     (cadr (gimp-drawable-offsets drawable)))
		;        ^^^^ - here we pick the second element of the
		;               returned list...

		(old-fg-color (car (gimp-palette-get-foreground)))
	      )

	    ; Ok, we are about to do multiple actions on the image, so
	    ; when the user wants to undo the effect he should not have
	    ; to wade through lots of script-generated steps. Hence
	    ; we create a undo group on our image.
	    (gimp-image-undo-group-start image)

	    ; Resize the image by 5 pixels in width and height respectively.
	    (gimp-image-resize image
	                       (+ width 5)
	                       (+ height 5)
	                       1
	                       1)

      ; Add a new layer on which we're going to draw.
      (set! layer (car (gimp-layer-new image (+ width 5) (+ height 5) RGBA-IMAGE "Stylized Shadow" 100 NORMAL))) 
      (gimp-image-add-layer image layer 0)
      
      ; First put a one-pixel-sized frame around the image...
      (gimp-palette-set-foreground '(0 0 0))

      (gimp-rect-select image 0 0 (+ width 2) (+ height 2) CHANNEL-OP-REPLACE 0 0)
      (gimp-rect-select image 1 1 width height CHANNEL-OP-SUBTRACT 0 0)
      
      (gimp-edit-fill layer FG-IMAGE-FILL)

      ; ... then go for the 3-pixel dropshadow.      
      (gimp-palette-set-foreground '(80 80 80))
      
      (gimp-rect-select image 3 (+ height 2) (+ width 2) 3 CHANNEL-OP-REPLACE 0 0)
      (gimp-rect-select image (+ width 2) 3 3 (+ height 2) CHANNEL-OP-ADD 0 0)

      (gimp-edit-fill layer FG-IMAGE-FILL)      

	    ; Restore the original foreground-color. It would be a good idea to do the
	    ; same for the selection...
	    (gimp-palette-set-foreground old-fg-color)
	    
	    ; ... but for now lets just unselect everything.
	    (gimp-selection-none image)	    

	    ; We are done with our actions. End the undo group
	    ; opened earlier. Be careful to properly end undo
	    ; groups again, otherwise the undo stack of the image
	    ; is messed up.
	    (gimp-image-undo-group-end image)

	    ; finally we notify the UI that something has changed.
	    (gimp-displays-flush)
	)
)

; Here we register the function in the GIMPs PDB.
; We have just one additional parameter to the default parameters:
; the user can choose the color for the script. For more available
; script-fu user interface elements see the "test-sphere.scm" script.
(script-fu-register "script-fu-stylized-shadow"
		    "<Image>/Script-Fu/Shadow/Stylized Shadow"
		    "script-fu stylized shadow"
		    "yours truly"
		    "yours truly"
		    "2006-12-14"
		    "RGB* GRAY*"
		    SF-IMAGE "Input Image" 0
		    SF-DRAWABLE "Input Drawable" 0
)
