require 'Sass'

module Sass::Script::Functions

  def atan(rad)
    rad = rad.value.to_f
    result = Math.atan(rad)
    Sass::Script::Number.new(result)
  end

  def deg_to_rad(degree)
    result = degree.value.to_f * Math::PI / 180
    Sass::Script::Number.new(result)
  end
  
  def rad_to_deg(rad)
    result = rad.value.to_f * 180 / Math::PI
    Sass::Script::Number.new(result)
  end

end